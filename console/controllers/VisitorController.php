<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Query;
use common\models\VisitorStats;

/**
 * Rebuilds visitor_stats from visitor_log.
 *
 * Usage: php yii visitor/aggregate [--days=7]
 */
class VisitorController extends Controller
{
    public function actionAggregate($days = 7)
    {
        $this->acquireLock();
        $this->stdout("Starting aggregation for last {$days} days...\n");

        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        $aggregates = [];

        // Only wipe daily rows in the window — never wipe week/month/year by date alone.
        VisitorStats::deleteAll([
            'and',
            ['stat_type' => VisitorStats::TYPE_DAILY],
            ['>=', 'stat_date', $startDate],
        ]);

        $aggregates = array_merge($aggregates, $this->buildDailyAggregates($startDate));

        // Rebuild calendar rollups from raw logs so week/month/year stay consistent.
        $periods = $this->periodsToRebuild();
        foreach ($periods as $period) {
            VisitorStats::deleteAll([
                'stat_type' => $period['type'],
                'stat_date' => $period['stat_date'],
            ]);
            $aggregates = array_merge(
                $aggregates,
                $this->buildRangeAggregates(
                    $period['type'],
                    $period['stat_date'],
                    $period['from'],
                    $period['to']
                )
            );
        }

        // Full all-time rebuild (single bucket per document_id / site)
        VisitorStats::deleteAll(['stat_type' => VisitorStats::TYPE_ALL_TIME]);
        $aggregates = array_merge(
            $aggregates,
            $this->buildRangeAggregates(
                VisitorStats::TYPE_ALL_TIME,
                '1970-01-01',
                null,
                null
            )
        );

        $this->insertAggregates($aggregates);

        $this->stdout('Aggregation complete. Inserted ' . count($aggregates) . " stat rows.\n");
        $this->releaseLock();
    }

    /**
     * Periods that the frontend/backend dashboards read.
     *
     * @return array<int, array{type:string,stat_date:string,from:string,to:string}>
     */
    protected function periodsToRebuild(): array
    {
        $today = date('Y-m-d');

        return [
            [
                'type' => VisitorStats::TYPE_WEEKLY,
                'stat_date' => date('Y-m-d', strtotime('monday this week')),
                'from' => date('Y-m-d', strtotime('monday this week')),
                'to' => $today,
            ],
            [
                'type' => VisitorStats::TYPE_WEEKLY,
                'stat_date' => date('Y-m-d', strtotime('monday last week')),
                'from' => date('Y-m-d', strtotime('monday last week')),
                'to' => date('Y-m-d', strtotime('sunday last week')),
            ],
            [
                'type' => VisitorStats::TYPE_MONTHLY,
                'stat_date' => date('Y-m-01'),
                'from' => date('Y-m-01'),
                'to' => $today,
            ],
            [
                'type' => VisitorStats::TYPE_MONTHLY,
                'stat_date' => date('Y-m-01', strtotime('first day of last month')),
                'from' => date('Y-m-01', strtotime('first day of last month')),
                'to' => date('Y-m-t', strtotime('last day of last month')),
            ],
            [
                'type' => VisitorStats::TYPE_YEARLY,
                'stat_date' => date('Y-01-01'),
                'from' => date('Y-01-01'),
                'to' => $today,
            ],
            [
                'type' => VisitorStats::TYPE_YEARLY,
                'stat_date' => date('Y-01-01', strtotime('first day of January last year')),
                'from' => date('Y-01-01', strtotime('first day of January last year')),
                'to' => date('Y-12-31', strtotime('last day of December last year')),
            ],
        ];
    }

    /**
     * @return array<int, array{stat_type:string,stat_date:string,document_id:?string,total_visits:int,unique_visits:int}>
     */
    protected function buildDailyAggregates(string $startDate): array
    {
        $totals = (new Query())
            ->select([
                'total_visits' => 'COUNT(*)',
                'stat_date' => 'visit_date',
                'document_id',
            ])
            ->from('{{%visitor_log}}')
            ->where(['>=', 'visit_date', $startDate])
            ->groupBy(['visit_date', 'document_id'])
            ->all();

        // Distinct fingerprints per day — closer to "pengunjung unik" than is_unique flag sum.
        $uniques = (new Query())
            ->select([
                'unique_visits' => 'COUNT(DISTINCT visitor_fingerprint)',
                'stat_date' => 'visit_date',
                'document_id',
            ])
            ->from('{{%visitor_log}}')
            ->where(['>=', 'visit_date', $startDate])
            ->groupBy(['visit_date', 'document_id'])
            ->all();

        return $this->mergeTotalAndUnique($totals, $uniques, VisitorStats::TYPE_DAILY);
    }

    /**
     * @return array<int, array{stat_type:string,stat_date:string,document_id:?string,total_visits:int,unique_visits:int}>
     */
    protected function buildRangeAggregates(string $type, string $statDate, ?string $from, ?string $to): array
    {
        $totalQuery = (new Query())
            ->select([
                'total_visits' => 'COUNT(*)',
                'document_id',
            ])
            ->from('{{%visitor_log}}')
            ->groupBy(['document_id']);

        $uniqueQuery = (new Query())
            ->select([
                'unique_visits' => 'COUNT(DISTINCT visitor_fingerprint)',
                'document_id',
            ])
            ->from('{{%visitor_log}}')
            ->groupBy(['document_id']);

        if ($from !== null) {
            $totalQuery->andWhere(['>=', 'visit_date', $from]);
            $uniqueQuery->andWhere(['>=', 'visit_date', $from]);
        }
        if ($to !== null) {
            $totalQuery->andWhere(['<=', 'visit_date', $to]);
            $uniqueQuery->andWhere(['<=', 'visit_date', $to]);
        }

        $totals = $totalQuery->all();
        $uniques = $uniqueQuery->all();

        return $this->mergeTotalAndUnique($totals, $uniques, $type, $statDate);
    }

    /**
     * @param array $totals
     * @param array $uniques
     * @return array<int, array{stat_type:string,stat_date:string,document_id:?string,total_visits:int,unique_visits:int}>
     */
    protected function mergeTotalAndUnique(array $totals, array $uniques, string $type, ?string $fixedStatDate = null): array
    {
        $aggregates = [];

        foreach ($totals as $row) {
            $statDate = $fixedStatDate ?? $row['stat_date'];
            $key = $type . ':' . $statDate . ':' . ($row['document_id'] ?: 'site');
            $aggregates[$key] = [
                'stat_type' => $type,
                'stat_date' => $statDate,
                'document_id' => $row['document_id'],
                'total_visits' => (int) $row['total_visits'],
                'unique_visits' => 0,
            ];
        }

        foreach ($uniques as $row) {
            $statDate = $fixedStatDate ?? $row['stat_date'];
            $key = $type . ':' . $statDate . ':' . ($row['document_id'] ?: 'site');
            if (!isset($aggregates[$key])) {
                $aggregates[$key] = [
                    'stat_type' => $type,
                    'stat_date' => $statDate,
                    'document_id' => $row['document_id'],
                    'total_visits' => 0,
                    'unique_visits' => 0,
                ];
            }
            $aggregates[$key]['unique_visits'] = (int) $row['unique_visits'];
        }

        return array_values($aggregates);
    }

    protected function insertAggregates(array $aggregates)
    {
        if (empty($aggregates)) {
            return;
        }

        $columns = ['stat_type', 'stat_date', 'document_id', 'total_visits', 'unique_visits'];
        $values = [];

        foreach ($aggregates as $row) {
            $values[] = [
                $row['stat_type'],
                $row['stat_date'],
                $row['document_id'],
                $row['total_visits'],
                $row['unique_visits'],
            ];
        }

        // Insert in chunks to avoid oversized packets
        foreach (array_chunk($values, 500) as $chunk) {
            Yii::$app->db->createCommand()
                ->batchInsert(VisitorStats::tableName(), $columns, $chunk)
                ->execute();
        }
    }

    protected function acquireLock()
    {
        $result = Yii::$app->db->createCommand("SELECT GET_LOCK('visitor_aggregate', 60)")->queryScalar();
        if (!$result) {
            throw new \Exception('Could not acquire aggregation lock. Another process may be running.');
        }
    }

    protected function releaseLock()
    {
        Yii::$app->db->createCommand("SELECT RELEASE_LOCK('visitor_aggregate')")->execute();
    }
}
