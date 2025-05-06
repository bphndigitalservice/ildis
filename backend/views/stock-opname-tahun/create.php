<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\StockOpnameTahun $model */

$this->title = 'Create Stock Opname Tahun';
$this->params['breadcrumbs'][] = ['label' => 'Stock Opname Tahuns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-opname-tahun-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>