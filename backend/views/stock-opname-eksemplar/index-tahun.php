<?php

use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BeritaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// $this->title = 'Stock Opname Eksemplar';
// $this->params['breadcrumbs'][] = $this->title;
?>


<?php Pjax::begin(['enablePushState' => false]); ?>

<div class="box-body table-responsive no-padding">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?= GridView::widget([

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Data ' . 'Berita' . '</h3>',
        ],
        'toolbar' =>  [
            ['content' => Html::a('<i class="fa fa-plus-circle"></i> Cek Buku', ['create'], ['class' => 'btn btn-success'])],
            '{export}',
            '{toggleData}'
        ],
        'dataProvider' => $dataProvider2,
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
            'kode_eksemplar',
            'tanggal',
            //'dokumen_id',
            [
                'attribute' => 'judul',
                'label' => 'Judul Monografi',
                'contentOptions' => ['style' => 'width:auto; white-space: normal;'],
                'content' => function ($data) {
                  return $data->dokumen->judul;
                  //  return $data->getJudul($data->dokumen_id);
                    //return Html::a(strtoupper($data->judul), ['view', 'id' => $data->id]);
              }
          ],
          'tahun',
          'created_by',
            // 'updated_at',

          [
            'class' => 'yii\grid\ActionColumn',
            'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
            'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
            'header' => 'Aksi',
            'template' => Helper::filterActionColumn('{delete}'),

            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['delete', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                },
            ],
        ],
    ],
]); ?>

<?php Pjax::end(); ?>
</div>