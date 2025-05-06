<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;

use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
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
]);
?>

<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <b>Form Tambah Status</b>
    </div>

    <div class="box-body">


        <?= $form->field($model, 'status_peraturan')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\Status::find()->where(['not', ['status' => ['Berlaku', 'Tidak Berlaku', 'tidak memiliki daya guna']]])->asArray()->all(), 'status', 'status'),
            [
                'prompt' => '--Silahkan Pilih--',
            ]
        )->label('Keterangan Status');
        ?>
        <?php 
	/*
	$form->field($model, 'id_dokumen_target')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\backend\models\Peraturan::find()->where(['tipe_dokumen' => 1])->asArray()->all(), 'id', 'judul'),
            'options' => ['placeholder' => 'Pilih Peraturan'],
            'pluginOptions' => [
                'allowClear' => true
            ]
        ])->label('Judul Peraturan');
*/
        ?>

        <?=  $form->field($model, 'id_dokumen_target')->widget(Select2::classname(), [
           
                'options' => [

                    'placeholder' => 'Pilih Peraturan...',
                    //'onchange' =>'return checkStatus(this.value)',
                //'onchange' =>'userAlert();'
                ],
                
                //'onchange'=>'return checkStatus(this.value)', //option

                'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 2,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => Url::to(['loadperaturan']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],


                ],
            ])->label('Judul Peraturan')->hint('Judul Peraturan wajib diisi jika status bukan - (minus)');

        ?>


        <?= $form->field($model, 'catatan_status_peraturan')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tanggal_perubahan')->widget(
            DateControl::classname(),
            [
                'type' => 'date',
                'ajaxConversion' => true,
                'autoWidget' => true,
                'widgetClass' => '',
                'displayFormat' => 'php:d-F-Y',
                'saveFormat' => 'php:Y-m-d',
                'widgetOptions' => [
                    'options' => ['placeholder' => 'tulis tanggal pengundangan'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'php:d-F-Y'
                    ]
                ]
            ]
        )->label('Tanggal Perubahan');
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
        )
        ?>
        <?= \yii\helpers\Html::a('<i class="fa fa-remove"></i> Batal', Yii::$app->request->referrer, ['class' => 'btn btn-danger btn-flat']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>