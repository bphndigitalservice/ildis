<?php

use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LogPustakawanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Log Pustakawan';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="box-body table-responsive no-padding">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?= GridView::widget([

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Data ' . 'Log Pustakawan' . '</h3>',
        ],
        'toolbar' =>  [

            '{export}',
            '{toggleData}'
        ],
        'dataProvider' => $dataProvider,
        'summary' => 'Ditampilkan {begin} - {end} dari {totalCount} Data',
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [

            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 50px;', 'class' => 'text-center'],
                'header' => 'No',
                'headerOptions' => ['class' => 'text-center'],
            ],


            [
                'attribute' => 'created_by',
                'label' => 'Operator',
                'headerOptions' => ['style' => 'width:200px'],
                'contentOptions' => ['style' => 'width:200; white-space: normal;'],
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => 'Pilih Operator'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        //  'width'=>'resolve',
                    ],
                ],
                'filter' => ArrayHelper::map(\backend\models\User::find()->all(), 'id', 'username'),
                'value' => function ($data) {
                    return $data->getUser($data->created_by);
                }, //'tipe_dokumen',
            ],


            // 'keterangan:ntext',
            [
                'label' => 'Aktifitas yang lakukan',
                'attribute' => 'keterangan',
                'format' => 'raw',
            ]

            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',



        ],
    ]); ?>

</div>