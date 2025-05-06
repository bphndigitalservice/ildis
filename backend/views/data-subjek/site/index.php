<?php

use backend\models\Peraturan;
// use backend\models\Monografi;
// use backend\models\Artikel;
// use backend\models\Putusan;
// use backend\models\Berita;
//use miloschuman\highcharts\Highcharts;
//use yii\web\JsExpression;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Dashboard');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= Peraturan::find()->where(['tipe_dokumen' => 1])->count(); ?></h3>

                <p>Peraturan</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <?= Html::a('More info <i class="fa fa-arrow-circle-right"></i>', ['peraturan/index'], ['class' => 'small-box-footer']) ?>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= Peraturan::find()->where(['tipe_dokumen' => 2])->count(); ?></h3>

                <p>Monografi</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <?= Html::a('More info <i class="fa fa-arrow-circle-right"></i>', ['monografi/index'], ['class' => 'small-box-footer']) ?>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= Peraturan::find()->where(['tipe_dokumen' => 3])->count(); ?></h3>

                <p>Artikel</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <?= Html::a('More info <i class="fa fa-arrow-circle-right"></i>', ['artikel/index'], ['class' => 'small-box-footer']) ?>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?= Peraturan::find()->where(['tipe_dokumen' => 4])->count(); ?></h3>

                <p>Putusan</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <?= Html::a('More info <i class="fa fa-arrow-circle-right"></i>', ['putusan/index'], ['class' => 'small-box-footer']) ?>
        </div>
    </div>
    <!-- ./col -->
</div>