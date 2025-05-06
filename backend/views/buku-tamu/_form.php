<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\BukuTamu $model */
/** @var yii\widgets\ActiveForm $form */
?>


<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-3',
            'offset' => 'col-sm-offset-5',
            'wrapper' => 'col-sm-7',
            //'error' => '',
            'hint' => '',
        ],
    ],
]); ?>

<div class="container">
    <div class="row">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
            <br><br><br>
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <b>FORM BUKU TAMU</b>

                </div>

                <div class="box-body">
                    <br>
                    <?= $form->field($model, 'nama_tamu')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'institusi_tamu')->textInput(['maxlength' => true]) ?>

                  
                    <?php
                    echo $form->field($model, 'anggota')->dropDownList(
                        ['Anggota' => 'Anggota', 'Bukan Anggota' => 'Bukan Anggota']
                    ); ?>

                    <?= $form->field($model, 'no_telp')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'keperluan')->textarea(['placeholder' => 'tulis lengkap judul peraturan', 'rows' => 6]) ?>



                    <?= Html::submitButton('SIMPAN', ['class' => 'btn btn-success']) ?>

                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-md-2">

        </div>
    </div>
</div>