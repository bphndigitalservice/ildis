<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\StockOpnameEksemplar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-opname-eksemplar-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <b>Form Data Eksemplar</b>

        </div>

        <div class="box-body">


            <?= $form->field($model, 'kode_eksemplar')->textInput()->label('Input Kode Eksemplar') ?>


            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Cek' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

        </div>
        <?php ActiveForm::end(); ?>

    </div>