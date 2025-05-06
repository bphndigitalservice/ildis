<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\InisialisasiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inisialisasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama_inisialisasi') ?>

    <?= $form->field($model, 'gmd') ?>

    <?= $form->field($model, 'tipe_koleksi') ?>

    <?= $form->field($model, 'lokasi') ?>

    <?php // echo $form->field($model, 'lokasi_rak') ?>

    <?php // echo $form->field($model, 'klasifikasi') ?>

    <?php // echo $form->field($model, 'tanggal_dimulai') ?>

    <?php // echo $form->field($model, 'tanggal_berakhir') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'pelaksana') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
