<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use kartik\editors\Summernote;
$this->title = 'Ubah Konfigurasi Frontend: ' . $model->nama_konfig;
$this->params['breadcrumbs'][] = ['label' => 'Konfigurasi Frontend', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Ubah';

?>


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
    ],
]); ?>

<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <b>Form Ubah Konfigurasi</b>

    </div>

    <div class="box-body">



      <?= $form->field($model, 'nama_konfig')->textInput(['maxlength' => true, 'disabled' => true])->label('Jenis Konfigurasi') ?>


      <?=
      $form->field($model, 'isi_konfig')->widget(Summernote::class, [
        'options' => ['placeholder' => '']
    ])->label('Ubah Kofigurasi');
    ?>



</div>
<div class="box-footer">
    <?= Html::submitButton(
        '<i class="fa fa-save"></i> Simpan',
        [
            'class' => 'btn btn-success btn-flat',
            'data' => [
                'confirm' => 'Yakin data sudah benar.',
                'method' => 'post',
                'data-pjax' => false
            ],
        ]
    ) ?>
    <?= \yii\helpers\Html::a('<i class="fa fa-remove"></i> Batal', Yii::$app->request->referrer, ['class' => 'btn btn-danger btn-flat']) ?>
</div>
</div>

<?php ActiveForm::end(); ?>

