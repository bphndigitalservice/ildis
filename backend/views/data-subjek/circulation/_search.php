<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CirculationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="circulation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'member_id') ?>

    <?= $form->field($model, 'member') ?>

    <?= $form->field($model, 'document_id') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'item_id') ?>

    <?php // echo $form->field($model, 'item_code') ?>

    <?php // echo $form->field($model, 'tanggal_pinjam') ?>

    <?php // echo $form->field($model, 'tanggal_kembali') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'denda') ?>

    <?php // echo $form->field($model, '_created_by') ?>

    <?php // echo $form->field($model, '_updated_by') ?>

    <?php // echo $form->field($model, '_created_time') ?>

    <?php // echo $form->field($model, '_updated_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
