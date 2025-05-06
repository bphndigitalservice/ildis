<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\StockOpnameTahun $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="stock-opname-tahun-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <b>Form Input Tahun Stock Opname </b>
        </div>
        <div class="box-body">

            <?= $form->field($model, 'tahun')->textInput() ?>


            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>