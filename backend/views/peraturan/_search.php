<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PeraturanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="peraturan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tipe_dokumen') ?>

    <?= $form->field($model, 'judul') ?>

    <?= $form->field($model, 'teu') ?>

    <?= $form->field($model, 'nomor_peraturan') ?>

    <?php // echo $form->field($model, 'nomor_panggil') ?>

    <?php // echo $form->field($model, 'bentuk_peraturan') ?>

    <?php // echo $form->field($model, 'singkatan_jenis') ?>

    <?php // echo $form->field($model, 'cetakan') ?>

    <?php // echo $form->field($model, 'tempat_terbit') ?>

    <?php // echo $form->field($model, 'penerbit') ?>

    <?php // echo $form->field($model, 'tanggal_penetapan') ?>

    <?php // echo $form->field($model, 'deskripsi_fisik') ?>

    <?php // echo $form->field($model, 'sumber') ?>

    <?php // echo $form->field($model, 'isbn') ?>

    <?php // echo $form->field($model, 'bahasa') ?>

    <?php // echo $form->field($model, 'bidang_hukum') ?>

    <?php // echo $form->field($model, 'nomor_induk_buku') ?>

    <?php // echo $form->field($model, 'jenis_peraturan') ?>

    <?php // echo $form->field($model, 'singkatan_bentuk') ?>

    <?php // echo $form->field($model, 'tipe_koleksi_nomor_eksemplar') ?>

    <?php // echo $form->field($model, 'pola_nomor_eksemplar') ?>

    <?php // echo $form->field($model, 'jumlah_eksemplar') ?>

    <?php // echo $form->field($model, 'kala_terbit') ?>

    <?php // echo $form->field($model, 'tahun_terbit') ?>

    <?php // echo $form->field($model, 'tanggal_dibacakan') ?>

    <?php // echo $form->field($model, 'pernyataan_tanggung_jawab') ?>

    <?php // echo $form->field($model, 'edisi') ?>

    <?php // echo $form->field($model, 'gmd') ?>

    <?php // echo $form->field($model, 'judul_seri') ?>

    <?php // echo $form->field($model, 'klasifikasi') ?>

    <?php // echo $form->field($model, 'info_detil_spesifik') ?>

    <?php // echo $form->field($model, 'abstrak') ?>

    <?php // echo $form->field($model, 'gambar_sampul') ?>

    <?php // echo $form->field($model, 'label') ?>

    <?php // echo $form->field($model, 'sembunyikan_di_opac') ?>

    <?php // echo $form->field($model, 'promosikan_ke_beranda') ?>

    <?php // echo $form->field($model, 'status_terakhir') ?>

    <?php // echo $form->field($model, 'status') ?>

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
