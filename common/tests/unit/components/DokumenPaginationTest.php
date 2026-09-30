<?php

namespace common\tests\unit\components;

use Codeception\Test\Unit;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;

/**
 * Documents the Yii2 pagination gotcha that broke /dokumen/:type?page=2.
 */
class DokumenPaginationTest extends Unit
{
    public function testReadingOffsetBeforeTotalCountClampsPageToZero(): void
    {
        $pagination = new Pagination([
            'pageSize' => 10,
            'params' => ['page' => 2, 'per-page' => 10],
        ]);

        $this->assertSame(0, $pagination->totalCount);
        $this->assertSame(0, $pagination->offset, 'offset stuck at 0 when totalCount is unset');
        $this->assertSame(0, $pagination->page);
    }

    public function testSyncingTotalCountBeforeOffsetUsesRequestedPage(): void
    {
        $pagination = new Pagination([
            'pageSize' => 10,
            'params' => ['page' => 2, 'per-page' => 10],
        ]);
        $pagination->totalCount = 25;

        $this->assertSame(1, $pagination->page);
        $this->assertSame(10, $pagination->offset);
    }

    public function testPoisonedPaginationKeepsFirstPageModels(): void
    {
        $provider = new ArrayDataProvider([
            'allModels' => range(1, 25),
            'pagination' => [
                'pageSize' => 10,
                'params' => ['page' => 2, 'per-page' => 10],
            ],
        ]);

        // Same order as the broken listing summary: total, then offset, then models.
        $provider->getTotalCount();
        $this->assertSame(0, $provider->pagination->offset);

        $models = $provider->getModels();
        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], array_values($models));
    }

    public function testSyncingTotalCountBeforeOffsetReturnsSecondPageModels(): void
    {
        $provider = new ArrayDataProvider([
            'allModels' => range(1, 25),
            'pagination' => [
                'pageSize' => 10,
                'params' => ['page' => 2, 'per-page' => 10],
            ],
        ]);

        $totalCount = $provider->getTotalCount();
        $provider->pagination->totalCount = $totalCount;

        $this->assertSame(10, $provider->pagination->offset);
        $this->assertSame(
            [11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            array_values($provider->getModels())
        );
    }
}
