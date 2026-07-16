<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\BootstrapInterface;
use common\models\VisitorLog;
use common\models\VisitorStats;

class VisitorCounter extends Component implements BootstrapInterface
{
    /** @deprecated Kept for config BC; unique visits are now once-per-calendar-day. */
    public $deduplicateWindowMinutes = 30;
    public $cookieName = '__visitor_id';
    public $cookieExpiryDays = 180;

    public function bootstrap($app)
    {
        // Track only on web frontend requests
        if ($app instanceof \yii\web\Application && $app->id === 'app-frontend') {
            if ($this->shouldSkipBootstrapTracking($app)) {
                return;
            }
            $this->trackVisit();
        }
    }

    /**
     * Downloads are not page views. Document detail pages still get site-wide
     * tracking here; per-document stats are added separately via DocumentCounter.
     */
    protected function shouldSkipBootstrapTracking($app): bool
    {
        $path = $app->request->pathInfo;
        if ($path === '') {
            return false;
        }

        return strpos($path, 'dokumen/download') === 0;
    }

    public function generateFingerprint($ip, $userAgent)
    {
        return md5($ip . '|' . $userAgent);
    }

    public function getVisitorCookieId()
    {
        $cookies = Yii::$app->request->cookies;
        $cookieId = $cookies->getValue($this->cookieName, null);

        if (!$cookieId) {
            $cookieId = $this->generateUuid();
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => $this->cookieName,
                'value' => $cookieId,
                'expire' => time() + 86400 * $this->cookieExpiryDays,
                'httpOnly' => true,
                'secure' => getenv('YII_ENV') === 'prod',
                'sameSite' => 'Lax',
            ]));
        }

        return $cookieId;
    }

    protected function generateUuid()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * True if this fingerprint has no prior log today for the given document scope.
     * (Once-per-day unique visitor — aligns with COUNT(DISTINCT visitor_fingerprint).)
     */
    public function isUniqueVisit($fingerprint, $documentId)
    {
        return $this->isFirstVisitInRange($fingerprint, $documentId, date('Y-m-d'));
    }

    /**
     * True if fingerprint has no prior visit on/after $fromDate for the document scope.
     */
    public function isFirstVisitInRange($fingerprint, $documentId, $fromDate)
    {
        $query = VisitorLog::find()
            ->where(['visitor_fingerprint' => $fingerprint])
            ->andWhere(['>=', 'visit_date', $fromDate]);

        if ($documentId !== null) {
            $query->andWhere(['document_id' => $documentId]);
        } else {
            $query->andWhere(['document_id' => null]);
        }

        return !$query->exists();
    }

    public function trackVisit($documentId = null, $pageUrl = null)
    {
        $request = Yii::$app->request;
        $ip = $request->userIP ?: '127.0.0.1';
        $userAgent = $request->userAgent ?: 'Unknown';
        $fingerprint = $this->generateFingerprint($ip, $userAgent);
        $cookieId = $this->getVisitorCookieId();
        $pageUrl = $pageUrl ?: $request->absoluteUrl;
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $monthStart = date('Y-m-01');
        $yearStart = date('Y-01-01');

        // Compute uniqueness before insert so the new row is not counted.
        $uniqueToday = $this->isFirstVisitInRange($fingerprint, $documentId, $today) ? 1 : 0;
        $uniqueWeek = $this->isFirstVisitInRange($fingerprint, $documentId, $weekStart) ? 1 : 0;
        $uniqueMonth = $this->isFirstVisitInRange($fingerprint, $documentId, $monthStart) ? 1 : 0;
        $uniqueYear = $this->isFirstVisitInRange($fingerprint, $documentId, $yearStart) ? 1 : 0;
        $uniqueAllTime = $this->isFirstVisitInRange($fingerprint, $documentId, '1970-01-01') ? 1 : 0;

        $log = new VisitorLog();
        $log->visitor_fingerprint = $fingerprint;
        $log->visitor_cookie_id = $cookieId;
        $log->document_id = $documentId;
        $log->page_url = $pageUrl;
        $log->visit_date = $today;
        $log->visit_time = $now;
        $log->is_unique = $uniqueToday;

        try {
            $log->save();
        } catch (\yii\db\Exception $e) {
            Yii::error('VisitorCounter insertion failed: ' . $e->getMessage());
            return false;
        }

        $this->upsertStat(VisitorStats::TYPE_DAILY, $today, $documentId, 1, $uniqueToday);
        $this->upsertStat(VisitorStats::TYPE_WEEKLY, $weekStart, $documentId, 1, $uniqueWeek);
        $this->upsertStat(VisitorStats::TYPE_MONTHLY, $monthStart, $documentId, 1, $uniqueMonth);
        $this->upsertStat(VisitorStats::TYPE_YEARLY, $yearStart, $documentId, 1, $uniqueYear);
        $this->upsertStat(VisitorStats::TYPE_ALL_TIME, '1970-01-01', $documentId, 1, $uniqueAllTime);

        return true;
    }

    protected function upsertStat($type, $statDate, $documentId, $totalDelta, $uniqueDelta)
    {
        $existing = VisitorStats::find()
            ->where([
                'stat_type' => $type,
                'stat_date' => $statDate,
                'document_id' => $documentId,
            ])
            ->one();

        if ($existing) {
            $existing->total_visits += $totalDelta;
            $existing->unique_visits += $uniqueDelta;
            $existing->save();
        } else {
            $stat = new VisitorStats();
            $stat->stat_type = $type;
            $stat->stat_date = $statDate;
            $stat->document_id = $documentId;
            $stat->total_visits = $totalDelta;
            $stat->unique_visits = $uniqueDelta;
            $stat->save();
        }
    }
}
