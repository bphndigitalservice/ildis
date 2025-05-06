<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\MemberTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Type Member';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="box-body table-responsive no-padding">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading'=>'Tabel Data Type Member',
        ],
        'toolbar' =>  [
            [   'content' => Html::a('Tambah Data', ['create'], ['class' => 'btn btn-success'])
        ],
        '{export}',
        '{toggleData}'
    ],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => "{items}\n{summary}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],


        'member_type_name',
        'loan_limit',
        'loan_periode',
       // 'enable_reserve',
      //  'reserve_limit',
      //  'member_periode',
      //  'reborrow_limit',
        'fine_each_day',
       // 'grace_periode',
        // 'input_date',
        // 'last_update',
        // 'id_tipe_koleksi',
        // 'id_tipe_gmd',
        // 'created_by',
        // 'updated_by',
        // 'created_at',
        // 'updated_at',

        [

            'class' => 'kartik\grid\ActionColumn',
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'width' => '50px',
            'header' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}',

            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-eye"></b></span>', ['view', 'id' => $model->id], ['title' => 'Lihat']);
                },


                'update' => function ($url, $model) {
                 
                    return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-edit"></b></span>', ['update', 'id' => $model->id], ['title' => 'ubah']);

                },

                'delete' => function ($url, $model) {
                    
                    return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['delete', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin ingin menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                    
                },


            ],
        ],
    ],
]); ?>

</div>
