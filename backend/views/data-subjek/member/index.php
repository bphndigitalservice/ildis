<?php

use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Member';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php Pjax::begin(['enablePushState' => false]); ?>

<div class="box-body table-responsive no-padding">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?= GridView::widget([

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Data ' . 'Member' . '</h3>',
        ],
        'toolbar' =>  [
            ['content' => Html::a('<i class="fa fa-plus-circle"></i> Tambah Data', ['create'], ['class' => 'btn btn-success'])],
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

            'personal_id_number',
            [
                'label'=>'User Login',
                'attribute'=>'username',
            ],
            
            'member_name',
            'inst_name',
            

                [
                    'attribute' => 'member_type_id',  
                    'label'=>'Type Member',   
                    'headerOptions' => ['style' => 'width:200px'],
                    'contentOptions' => ['style' => 'width:200; white-space: normal;'],
                    'filterType' => GridView::FILTER_SELECT2,    
                    'filterWidgetOptions' => [
                        'options' => ['prompt' => 'Pilih Jenis'],
                        'pluginOptions' => [
                            'allowClear' => true,
                  //  'width'=>'resolve',
                        ],
                    ],                     
                    'filter' => ArrayHelper::map(\backend\models\MemberType::find()->all(), 'id', 'member_type_name'),
                    'value' => 'member.member_type_name',
                ],            
            // 'member_address:ntext',
            // 'member_email:email',

            // 'member_image:ntext',
            'member_since_date',
            'register_date',
            'expire_date',
           // 'status',
            // 'phone_number',
            // 'fax_number',
            // 'member_notes:ntext',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'update_by',


            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 180px;', 'class' => 'text-center'],
                'contentOptions' => ['style' => 'width: 180px;', 'class' => 'text-center'],
                'header' => 'Aksi',
                'template' => Helper::filterActionColumn('{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{reset}'),

                'buttons' => [
                    'view' => function ($url, $model) {
                        return
                            Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-search-plus"></b></span>', ['view', 'id' => $model->id], ['title' => 'Lihat']);
                    },
                    'update' => function ($id, $model) {
                        return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['update', 'id' => $model->id], ['title' => 'Ubah']);
                    },

                    'reset' => function ($url, $model) {
                        $url = Url::to(['password', 'id' => $model->id]);
                        return Html::a('<span class="fa fa-pencil-square-o">', $url, [
                            'title'        => 'reset password',
                            'class' => 'btn btn-primary btn-sm',
                            'data-confirm' => Yii::t('yii', 'Yakin mereset password user ini?'),
                            'data-method'  => 'post',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['delete', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>