<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\MemberTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Member Types';
$this->params['breadcrumbs'][] = $this->title;
?>


    
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([

            'panel' => [
                'type' => GridView::TYPE_PRIMARY
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

                'id',
                'member_type_name',
                'loan_limit',
                'loan_periode',
                'enable_reserve',
                // 'reserve_limit',
                // 'member_periode',
                // 'reborrow_limit',
                // 'fine_each_day',
                // 'grace_periode',
                // 'input_date',
                // 'last_update',
                // 'id_tipe_koleksi',
                // 'id_tipe_gmd',
                // 'created_by',
                // 'updated_by',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
   
</div>
