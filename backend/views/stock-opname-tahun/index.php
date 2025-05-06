<?php

use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BeritaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stock Opname Tahun';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php Pjax::begin(['enablePushState' => false]); ?>

<div class="box-body table-responsive no-padding">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?= GridView::widget([

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Data ' . 'Stockopname Tahun' . '</h3>',
        ],
        'toolbar' =>  [
            ['content' => Html::a('<i class="fa fa-plus-circle"></i> Buat Laporan', ['create'], ['class' => 'btn btn-success'])],
            '{export}',
            '{toggleData}'
        ],
        'dataProvider' => $dataProvider,
        'summary' => 'Ditampilkan {begin} - {end} dari {totalCount} Data',
        // 'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [

            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 20px;', 'class' => 'text-center'],
                'header' => 'No',
                'headerOptions' => ['class' => 'text-center'],
            ],

            'tahun',
            [
                'label' => 'Jumlah Seluruh Buku ',
                'value' => function ($data) {
                    return $data->getBuku($data->tahun);
                }
            ],
            [
                'label' => 'Jumlah Eksemplar',
                'value' => function ($data) {
                    return $data->getEksemplar($data->tahun);
                }
            ],

            [
                'label' => 'Jumlah Buku Stock Opname',
                'value' => function ($data) {
                    return $data->getBukuStock($data->tahun);
                }
            ],



            [
                'label' => 'Jumlah Eksemplar Stockopname',
                'value' => function ($data) {
                    return $data->getEksemplarStock($data->tahun);
                }
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'header' => 'Aksi',
                'template' => Helper::filterActionColumn('{cetak}'),

                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['delete', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                    },

                    'cetak' => function ($id, $model) {
                        return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-print"></b></span>', ['cetak', 'id' => $model->tahun], ['title' => 'cetak']);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>