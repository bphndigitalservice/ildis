<?php
use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DataSubjekSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Subyek';
$this->params['breadcrumbs'][] = $this->title;
?>


    
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([

            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading'=> '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Data '.'Data Subyek'.'</h3>',
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
                    'header'=>'No',
                    'headerOptions' => ['class' => 'text-center'],
                ],                 

                
                'subyek',

            [
                //'attribute' => 'Jumlah',
                'label' => 'Jumlah Monografi',
                 'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'content' => function ($data) {

                    return $data->getJumlahData($data->subyek);
                    //return Html::a(strtoupper($data->judul), ['view', 'id' => $data->id]);
                }
            ],                  
            

                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                    'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                    'header' =>'Aksi',
                    'template' => Helper::filterActionColumn('{view}'),
                    
                    'buttons' => [
                        'view' => function($url, $model) {
                                    return 
                                    Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-search-plus"></b></span>', ['view', 'id' => $model->id], ['title' => 'Lihat']);
                                },
                        'update' => function($id, $model) {
                                    return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['update', 'id' => $model->id], ['title' => 'Ubah']);
                                },
                        'delete' => function($url, $model) {
                                    return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['delete', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                                },
                    ],
                ],                
            ],
        ]); ?>
   
</div>
