<?php

use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PeraturanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Subyek : ' . $id;
$this->params['breadcrumbs'][] = ['label' => 'Monografi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $id;

?>

<?php Pjax::begin(['enablePushState' => false]); ?>

<div class="box-body table-responsive no-padding">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?= GridView::widget([

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Data ' . 'Data Produk Hukum' . '</h3>',
        ],
        'toolbar' =>  [

            '{export}',
            '{toggleData}'
        ],
        'dataProvider' => $subyek,
        'summary' => 'Ditampilkan {begin} - {end} dari {totalCount} Data',
        //'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [

            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 50px;', 'class' => 'text-center'],
                'header' => 'No',
                'headerOptions' => ['class' => 'text-center'],
            ],


            [
                'label' => 'ID Dokumen',
                'attribute' => 'id_dokumen',
                'format' => 'raw',
                'value' => function ($data) {
                    //return $data->id_dokumen;
                    return Html::a(strtoupper($data->id_dokumen), ['view', 'id' => $data->id_dokumen]);
                }
            ],

            [
                'attribute' => 'id_dokumen',
                'label' => 'Tipe Dokumen',
                'value' => function ($data) {
                    return $data->getTipe($data->id_dokumen);
                }
            ],

            [
                'attribute' => 'id_dokumen',
                'label' => 'Judul',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getJudul($data->id_dokumen);
                }
            ],

            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
            //     'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
            //     'header' => 'Aksi',
            //     'template' => Helper::filterActionColumn('{view}'),

            //     'buttons' => [
            //         'view' => function ($url, $model) {
            //             return
            //                 Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-search-plus"></b></span>', ['view', 'id' => $model->id], ['title' => 'Lihat']);
            //         },


            //     ],
            // ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>