<?php

namespace common\tests\unit\components;

use Codeception\Test\Unit;
use frontend\controllers\BeritaController;
use yii\base\Module;
use yii\web\ForbiddenHttpException;

/**
 * GHSA-prrm-3g6v-35vv: frontend write actions must remain denied.
 */
class BeritaWriteDeniedTest extends Unit
{
    private function controller(): BeritaController
    {
        return new BeritaController('berita', new Module('test'));
    }

    public function testCreateIsForbidden(): void
    {
        $this->expectException(ForbiddenHttpException::class);
        $this->controller()->actionCreate();
    }

    public function testUpdateIsForbidden(): void
    {
        $this->expectException(ForbiddenHttpException::class);
        $this->controller()->actionUpdate(1);
    }

    public function testDeleteIsForbidden(): void
    {
        $this->expectException(ForbiddenHttpException::class);
        $this->controller()->actionDelete(1);
    }
}
