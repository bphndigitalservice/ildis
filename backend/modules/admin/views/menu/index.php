<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Menu */

$this->title = Yii::t('rbac-admin', 'Menus');
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
                [   'content' => Html::a('Buat Menu', ['create'], ['class' => 'btn btn-success'])
                ],
                //'{export}',
               /// '{toggleData}'
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    [
                        'attribute' => 'menuParent.name',
                        'filter' => Html::activeTextInput($searchModel, 'parent_name', [
                            'class' => 'form-control', 'id' => null
                        ]),
                        'label' => Yii::t('rbac-admin', 'Parent'),
                    ],
                    'route',
                    'order',
                    'data',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
        ]); ?>
   
    <?php Pjax::end(); ?>
</div>
