<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\EksemplarSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="eksemplar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_dokumen') ?>

    <?= $form->field($model, 'kode_eksemplar') ?>

    <?= $form->field($model, 'no_panggil') ?>

    <?= $form->field($model, 'kode_inventaris') ?>

    <?php // echo $form->field($model, 'id_lokasi') ?>

    <?php // echo $form->field($model, 'lokasi_rak') ?>

    <?php // echo $form->field($model, 'tipe_lokasi') ?>

    <?php // echo $form->field($model, 'status_eksemplar') ?>

    <?php // echo $form->field($model, 'nomor_pemesanan') ?>

    <?php // echo $form->field($model, 'tgl_pemesanan') ?>

    <?php // echo $form->field($model, 'tgl_penerimaan') ?>

    <?php // echo $form->field($model, 'agen') ?>

    <?php // echo $form->field($model, 'sumber_perolehan') ?>

    <?php // echo $form->field($model, 'faktur') ?>

    <?php // echo $form->field($model, 'tgl_faktur') ?>

    <?php // echo $form->field($model, 'harga') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
