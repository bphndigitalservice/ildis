<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\StockOpnameMonografi $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="stock-opname-monografi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_dokumen')->textInput() ?>

    <?= $form->field($model, 'tahun')->textInput() ?>

    <?= $form->field($model, 'jumlah_eksemplar')->textInput() ?>

    <?= $form->field($model, 'jumlah_scan')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
