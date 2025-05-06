<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Abstrak $model */
/** @var yii\widgets\ActiveForm $form */
$this->title = 'Tambah Data Abstrak';
$this->params['breadcrumbs'][] = ['label' => 'Peraturan', 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="abstrak-form">
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'options' => ['enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'offset' => 'col-sm-offset-4',
                'wrapper' => 'col-sm-8',
                //'error' => '',
                'hint' => '',
            ],
        ]
    ]);
    ?>

    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <b>Form Tambah Abstrak</b>

        </div>

        <div class="box-body">
            <?= $form->field($model, 'subjek')->textInput(['maxlength' => true, 'value' => substr($simpan, 0, -1), 'disabled' => true]) ?>

            <?= $form->field($model, 'tahun')->textInput(['value' => $peraturan->tahun_terbit, 'disabled' => true]) ?>
            <?= $form->field($model, 'singkatan')->textInput(['maxlength' => true, 'placeholder' => 'singkatan peraturan sesuai petunjuk teknis']) ?>

            <?= $form->field($model, 'judul')->textInput(['maxlength' => true, 'placeholder' => 'isikan judul tanpa nomor dan tahun']) ?>

            <?= $form->field($model, 'isi_abstrak_1')->textarea(['rows' => 3, 'placeholder' => 'dasar menimbang peraturan'])->label('Abstrak') ?>

            <?= $form->field($model, 'isi_abstrak_2')->textarea(['rows' => 3, 'placeholder' => 'dasar mengingat peraturan'])->label('') ?>

            <?= $form->field($model, 'isi_abstrak_3')->textarea(['rows' => 3, 'placeholder' => 'materi pokok'])->label('') ?>

            <?= $form->field($model, 'catatan_1')->textarea(['rows' => 3, 'isikan sesuai petunjuk teknis'])->label('Catatan') ?>
            <?= $form->field($model, 'catatan_2')->textarea(['rows' => 3, 'isikan sesuai petunjuk teknis'])->label('') ?>
            <?= $form->field($model, 'catatan_3')->textarea(['rows' => 3, 'isikan sesuai petunjuk teknis'])->label('') ?>
            <?= $form->field($model, 'catatan_4')->textarea(['rows' => 3, 'isikan sesuai petunjuk teknis'])->label('') ?>
            <?= $form->field($model, 'catatan_5')->textarea(['rows' => 3, 'isikan sesuai petunjuk teknis'])->label('') ?>


            <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
