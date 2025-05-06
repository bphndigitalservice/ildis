<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\StockOpnameMonografi $model */

$this->title = 'Create Stock Opname Monografi';
$this->params['breadcrumbs'][] = ['label' => 'Stock Opname Monografis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-opname-monografi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
