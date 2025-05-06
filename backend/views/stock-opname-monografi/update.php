<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\StockOpnameMonografi $model */

$this->title = 'Update Stock Opname Monografi: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Opname Monografis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-opname-monografi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
