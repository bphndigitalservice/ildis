<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */

$monografi = \backend\models\Monografi::findOne($id);
$model->no_panggil = $monografi->nomor_panggil;
$model->sumber_perolehan = $monografi->sumber_perolehan;
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
<?= $form->errorSummary($model) ?>
<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <b>Form Tambah Data Eksemplar</b>
    </div>

    <div class="box-body">

        <?= $form->field($model, 'kode_eksemplar')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'no_panggil')->textInput(['maxlength' => true, 'disabled' => true]) ?>

        <?= $form->field($model, 'kode_inventaris')->textInput(['maxlength' => true]) ?>



        <?= $form->field($model, 'lokasi_rak')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tipe_lokasi')->textInput(['maxlength' => true]) ?>



        <?= $form->field($model, 'status_eksemplar')->dropDownList(
            [
                'Tersedia' => 'Tersedia',
                'Dipinjam' => 'dipinjam',
                'Rusak' => 'Rusak',
            ],
            ['prompt' => '--Silahkan Pilih--',]
        );
        ?>

        <?= $form->field($model, 'nomor_pemesanan')->textInput(['maxlength' => true]) ?>


        <?= $form->field($model, 'tgl_pemesanan')->widget(
            DateControl::classname(),
            [
                'type' => 'date',
                'ajaxConversion' => true,
                'autoWidget' => true,
                'widgetClass' => '',
                'displayFormat' => 'php:d-F-Y',
                'saveFormat' => 'php:Y-m-d',
                'widgetOptions' => [
                    'options' => ['placeholder' => 'tulis tanggal penetapan '],
                    'pluginOptions' => [

                        'autoclose' => true,
                        'format' => 'php:d-F-Y'
                    ]
                ]
            ]
        )->label('Tanggal Pemesanan');
        ?>

        <?= $form->field($model, 'tgl_penerimaan')->widget(
            DateControl::classname(),
            [
                'type' => 'date',
                'ajaxConversion' => true,
                'autoWidget' => true,
                'widgetClass' => '',
                'displayFormat' => 'php:d-F-Y',
                'saveFormat' => 'php:Y-m-d',
                'widgetOptions' => [
                    'options' => ['placeholder' => 'tulis tanggal penetapan '],
                    'pluginOptions' => [

                        'autoclose' => true,
                        'format' => 'php:d-F-Y'
                    ]
                ]
            ]
        )->label('Tanggal Penerimaan');
        ?>

        <?= $form->field($model, 'agen')->textInput(['maxlength' => true]) ?>


        <?= $form->field($model, 'sumber_perolehan')->textInput(['maxlength' => true, 'disabled' => true]) ?>

        <?= $form->field($model, 'faktur')->textInput(['maxlength' => true]) ?>



        <?= $form->field($model, 'tgl_faktur')->widget(
            DateControl::classname(),
            [
                'type' => 'date',
                'ajaxConversion' => true,
                'autoWidget' => true,
                'widgetClass' => '',
                'displayFormat' => 'php:d-F-Y',
                'saveFormat' => 'php:Y-m-d',
                'widgetOptions' => [
                    'options' => ['placeholder' => 'tulis tanggal penetapan '],
                    'pluginOptions' => [

                        'autoclose' => true,
                        'format' => 'php:d-F-Y'
                    ]
                ]
            ]
        )->label('Tanggal Faktur');
        ?>
        <?= $form->field($model, 'harga')->textInput() ?>


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
