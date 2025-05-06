<?php

use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BeritaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Konfigurasi Frontend';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php Pjax::begin(['enablePushState' => false]); ?>

<div class="box-body table-responsive no-padding">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?= GridView::widget([

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Data ' . 'Konfigurasi Frontend' . '</h3>',
        ],
        // 'toolbar' =>  [
        //     ['content' => Html::a('<i class="fa fa-plus-circle"></i> Tambah Data', ['create'], ['class' => 'btn btn-success'])],
        //     '{export}',
        //     '{toggleData}'
        // ],
        'dataProvider' => $dataProvider,
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


            //'tanggal',
            // [
            //     'attribute'=>'tanggal',
            //     'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-left'],
            //     'value'=>function($data){
            //         return $data->getTanggal($data->tanggal);
            //     }
            // ],

            //'judul',
            [
                'attribute'=>'nama_konfig',
                'contentOptions' => ['style' => 'width: 300px;', 'class' => 'text-left'],
            ],

            [
                'attribute'=>'jenis',
                'contentOptions' => ['style' => 'width: 300px;', 'class' => 'text-left'],
            ],
            [
                'attribute'=>'isi_konfig',
                'format'=>'raw',
                'label'=>'Status',
                'contentOptions' => ['style' => 'width: 300px;', 'class' => 'text-left'],
                'value'=> function($data){
                    if ($data->isi_konfig==$data->default){

                        return  '<span class="label label-warning">Belum dirubah</span>';
                    }else{
                        return  '<span class="label label-success">Telah dirubah</span>';
                    }
                }
            ],
          //   [
          //     'label'=>'Isi Berita',
          //     'format'=>'raw',
          //     'attribute'=>'isi',
          // ],	
            //'image:ntext',
            // 'status',
            // 'created_at',
            // 'created_by',
            // 'update_at',
            // 'updated_by',


            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'header' => 'Aksi',
                'template' => Helper::filterActionColumn('{view}&nbsp;&nbsp;{update}'),

                'buttons' => [
                    'view' => function ($url, $model) {
                        return
                        Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-search-plus"></b></span>', ['view', 'id' => $model->id], ['title' => 'Lihat']);
                    },
                    'update' => function ($id, $model) {

                        switch ($model->id) {
                            case '2':
                            case '6':
                            case '7':
                            case '13':
                            case '14':
                            case '15':


                            return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['update', 'id' => $model->id], ['title' => 'Ubah']);
                            break;

                            case '1':
                            case '12':
                            case '20':
                            case '21':
                            return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['upload', 'id' => $model->id], ['title' => 'Ubah']);
                            break;

                            case '3':
                            case '4':
                            case '5':
                            case '8':
                            case '9':
                            case '10':
                            case '11':
                            case '16':
                            case '17':
                            case '18':
                            case '19':
                            return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['deskripsi', 'id' => $model->id], ['title' => 'Ubah']);
                            break;
                            

                            // case '12':
                            // case '13':
                            // case '14':
                            // return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['upload', 'id' => $model->id], ['title' => 'Ubah']);
                            // break;
                            

                        }
                        
                    },

                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
