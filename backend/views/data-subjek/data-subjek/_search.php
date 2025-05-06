<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DataSubjekSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-subyek-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_dokumen') ?>

    <?= $form->field($model, 'subyek') ?>

    <?= $form->field($model, 'tipe_subyek') ?>

    <?= $form->field($model, 'jenis_subyek') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'integrasi') ?>

    <?php // echo $form->field($model, '_created_by') ?>

    <?php // echo $form->field($model, '_updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
