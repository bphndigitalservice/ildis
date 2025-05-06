
<?php

use backend\models\PcounterUsers;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\PcounterUsersSeacrh $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pcounter Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pcounter-users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pcounter Users', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_ip',
            'user_time:datetime',
            'creation_date',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PcounterUsers $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
