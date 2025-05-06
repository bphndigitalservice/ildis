<?php

use yii\helpers\Html;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Stock Opname Eksemplar';
$this->params['breadcrumbs'][] = ['label' => 'Eksemplar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs dashboard_tabs_cl">
        <li class="active"><a href="#tab_1" data-toggle="tab">Tahun <?= date('Y') ?></a></li>
        <li><a href="#tab_2" data-toggle="tab">Data Sebelumnya</a></li>

    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <?= $this->render('index-tahun', [
                'searchModel' => $searchModel,
                'dataProvider2' => $dataProvider2,
            ])
            ?>
        </div>
        <div class="tab-pane" id="tab_2">
            <?= $this->render('index-all', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ])
            ?>
        </div>

    </div>