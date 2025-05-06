<?php
use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\EksemplarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Eksemplar';
$this->params['breadcrumbs'][] = $this->title;
?>


    <?php Pjax::begin(['enablePushState' => false]); ?>
    
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([

            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading'=> '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Data '.'Eksemplar'.'</h3>',
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

              
                          [
                'attribute' => 'kode_eksemplar',
                'label' => 'Kode Eksemplar',
                'contentOptions' => ['style' => 'width:auto; white-space: normal;'],
                'content' => function ($data) {

                    //return $data->getJudul($data->id);
                    return Html::a(strtoupper($data->kode_eksemplar), ['monografi/view', 'id' => $data->id_dokumen]);
                }
            ],
              
                'no_panggil',
                'kode_inventaris',
     


                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                    'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                    'header' =>'Aksi',
                    'template' => Helper::filterActionColumn('{view}&nbsp;&nbsp;'),
                    
                    'buttons' => [
                        'view' => function($url, $model) {
                                    return 
                                    Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-search-plus"></b></span>', ['view', 'id' => $model->id_dokumen], ['title' => 'Lihat']);
                                },
                      
                    ],
                ],                
            ],
        ]); ?>
   
    <?php Pjax::end(); ?>
</div>
