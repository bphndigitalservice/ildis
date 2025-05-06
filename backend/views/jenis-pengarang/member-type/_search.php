<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\searchs\MemberTypeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-type-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'member_type_name') ?>

    <?= $form->field($model, 'loan_limit') ?>

    <?= $form->field($model, 'loan_periode') ?>

    <?= $form->field($model, 'enable_reserve') ?>

    <?php // echo $form->field($model, 'reserve_limit') ?>

    <?php // echo $form->field($model, 'member_periode') ?>

    <?php // echo $form->field($model, 'reborrow_limit') ?>

    <?php // echo $form->field($model, 'fine_each_day') ?>

    <?php // echo $form->field($model, 'grace_periode') ?>

    <?php // echo $form->field($model, 'input_date') ?>

    <?php // echo $form->field($model, 'last_update') ?>

    <?php // echo $form->field($model, 'id_tipe_koleksi') ?>

    <?php // echo $form->field($model, 'id_tipe_gmd') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
