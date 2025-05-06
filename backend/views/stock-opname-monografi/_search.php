<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\search\StockOpnameMonografiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="stock-opname-monografi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_dokumen') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'jumlah_eksemplar') ?>

    <?= $form->field($model, 'jumlah_scan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
