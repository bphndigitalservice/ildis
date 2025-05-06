<?php

use yii\helpers\Html;


$this->title = 'Eksemplar';
$this->params['breadcrumbs'][] = ['label' => 'Eksemplar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model backend\models\StockOpnameEksemplar */

?>
<div class="stock-opname-eksemplar-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>