<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PcounterUsers $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pcounter-users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_time')->textInput() ?>

    <?= $form->field($model, 'creation_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
