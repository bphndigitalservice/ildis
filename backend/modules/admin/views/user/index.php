<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use mdm\admin\components\Helper;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rbac-admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php Pjax::begin(); ?>
    
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'panel' => [
                'type' => GridView::TYPE_PRIMARY
            ],
            'toolbar' =>  [
                [   'content' => Html::a('Tambah Data', ['signup'], ['class' => 'btn btn-success'])
                ],
                '{export}',
                '{toggleData}'
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'username',
                'email:email',
                [
                    'attribute' => 'status',
                    'filter' => [10 => 'Active', 0 => 'Non Aktif'],
                    'format' => 'raw',
                    'options' => [
                        'width' => '100px',
                    ],
                    'value' => function ($data) {
                        if ($data->status == 10)
                            return "<span class='label label-primary'>" . 'Active' . "</span>";
                        else
                            return "<span class='label label-warning'>" . 'Non AKtif' . "</span>";
                    }
                ],             
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => Helper::filterActionColumn(['view', 'activate', 'reset','access']),
                'buttons' => [

                    
                    'view' => function ($url, $model) {
                        $url = Url::to(['view', 'id' => $model->id]);
                        return Html::a('<span class="fa fa-eye"></span>', $url, ['title' => 'view','class'=>'btn btn-primary btn-sm']);
                    },
                    
                    // 'activate' => function($url, $model) {
                    //     if ($model->status == 0) {                            
                    //         $options = [
                    //             'title' => Yii::t('rbac-admin', 'Activate'),
                    //             'aria-label' => Yii::t('rbac-admin', 'Activate'),
                    //             'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
                    //             'data-method' => 'post',
                    //             'data-pjax' => '0',
                    //             'class'=>'btn btn-success btn-sm user-active',
                    //         ];
                    //     return Html::a('<span class="fa fa-check-square"></span>', $url, $options);
                    //     }else{
                    //         $options = [
                    //             'title' => Yii::t('rbac-admin', 'Inactivate'),
                    //             'aria-label' => Yii::t('rbac-admin', 'Inactivate'),
                    //             'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to inactivate this user?'),
                    //             'data-method' => 'post',
                    //             'data-pjax' => '0',
                    //             'class'=>'btn btn-danger btn-sm user-inactive',
                    //         ];
                    //     return Html::a('<span class="fa fa-ban"></span>', $url, $options);
                    //     }
                    // }
                    'activate' => function ($url,$model) {
                        if($model->id == \Yii::$app->user->identity->id)
                        {return '';}else{

                        if ($model->status == 10){
                            return Html::a(
                            '<span class="fa fa-check-square"></span>', 
                                ['inactivate', 'id'=>$model->id], 
                                    [
                                        'title'=>'nonaktifkan user',
                                        'class' => 'btn btn-success btn-sm user-inactive',
                                        'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to inactivate this user?'),
                                        'data-method' => 'post',
                                    ]
                            );
                        }else{
                            return Html::a(
                            '<span class="fa fa-ban"></span>', 
                                ['activate', 'id'=>$model->id], 
                                    [
                                        'title'=>'aktifkan user',
                                        'class' => 'btn btn-warning btn-sm user-active',
                                        'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
                                        'data-method' => 'post'
                                    ]
                            );
                        }
                        
                    } 
                        },
                    'reset' => function ($url, $model) {
                        $url = Url::to(['requestPasswordResetToken', 'id' => $model->id]);
                        return Html::a('<span class="fa fa-pencil-square-o">', $url, [
                            'title'        => 'reset password',
                            'class' => 'btn btn-danger btn-sm',
                            'data-confirm' => Yii::t('yii', 'Yakin mereset password user ini?'),
                            'data-method'  => 'post',
                        ]);
                        }, 
                    'access' => function ($url, $model) {
                        $url = Url::to(['assignment/view', 'id' => $model->id]);
                        return Html::a('<span class="fa fa-key"></span>', $url, ['title' => 'view','class'=>'btn btn-default btn-sm']);
                    },                        

                ],                    
                    
            ],
        ],
    ]); ?>
   
    <?php Pjax::end(); ?>
</div>


