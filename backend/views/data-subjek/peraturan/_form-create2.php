<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="section">

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
    <?= $form->errorSummary([$model, $lampiran]) ?>
    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <b>Form Data Utama</b>

        </div>

        <div class="box-body">

            <?= $form->field($model, 'jenis_peraturan')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\JenisPeraturan::find()->where(['parent_id' => 1])->asArray()->all(), 'id', 'name'),
                [
                    'prompt' => 'Pilih Type Pengelolaan',
                    'onchange' => '
                                        $.get( "' . Url::toRoute('/peraturan/jenis') . '", { id: $(this).val() } )
                                            .done(function( data ) {
                                                $( "#' . Html::getInputId($model, 'bentuk_peraturan') . '" ).html( data );

                                            }
                                        );'
                ]
            )->label('Jenis Peraturan');
            ?>

            <?= $form->field($model, 'bentuk_peraturan')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\JenisPeraturan::find()->where(['parent_id' => $model->jenis_peraturan])->asArray()->all(), 'name', 'singkatan'),
                [
                    'prompt' => 'Pilih Bentuk Peraturan',
                ]
            )->label('Bentuk Peraturan')
            ?>
            <?= $form->field($model, 'judul')->textarea(['placeholder' => 'tulis lengkap judul peraturan', 'rows' => 6]) ?>

            <?= $form->field($model, 'nomor_peraturan')->textInput(['placeholder' => 'tulis nomor peraturan', 'maxlength' => true]) ?>

            <?= $form->field($model, 'tahun_terbit')->textInput(['placeholder' => 'tulis tahun peraturan', 'maxlength' => 4])->label('Tahun') ?>

            <?=
                $form->field($model, 'tempat_terbit')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Daerah::find()->asArray()->all(), 'nama', 'nama'),
                    'options' => ['placeholder' => 'Pilih Tempat Penetapan'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label('Tempat Penetapan');;
            ?>

            <?= $form->field($model, 'tanggal_penetapan')->widget(
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
            )->label('Tanggal Penetapan');
            ?>
            <?= $form->field($model, 'penandatanganan')->textInput(['placeholder' => 'tulis penandatangan peraturan', 'maxlength' => true]) ?>

            <?= $form->field($model, 'tanggal_pengundangan')->widget(
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
            )->label('Tanggal Pengundangan');
            ?>
            <?= $form->field($model, 'pemrakarsa')->textInput(['placeholder' => 'tulis pemrakarsa', 'maxlength' => true]) ?>

            <?= $form->field($model, 'sumber')->textarea(['placeholder' => 'contoh LN Nomor 21 Tahun 2017 ', 'rows' => 6]) ?>

            <?= $form->field($model, 'bahasa')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\Bahasa::find()->asArray()->all(), 'name', 'name'),
                [
                    'prompt' => '--Silahkan Pilih--',
                ]
            )->label('Bahasa');
            ?>

            <?= $form->field($model, 'urusan_pemerintahan')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\UrusanPemerintahan::find()->asArray()->all(), 'name', 'name'),
                [
                    'prompt' => '---Silahkan Pilih--',
                ]
            )->label('Urusan Pemerintahan');
            ?>
            <?= $form->field($model, 'bidang_hukum')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\BidangHukum::find()->asArray()->all(), 'name', 'name'),
                [
                    'prompt' => '--Silahkan Pilih--',
                ]
            )->label('Bidang Hukum');
            ?>




            <!-- $form->field($model, 'status')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\Status::find()->where(['status' => ['Berlaku', 'Tidak Berlaku']])->asArray()->all(), 'status', 'status'),
                [
                    'prompt' => '--Silahkan Pilih--',
                ]
            )->label('Status Peraturan');
           

             $form->field($model, 'status_terakhir')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\Status::find()->where(['not', ['status' => ['Berlaku', 'Tidak Berlaku']]])->asArray()->all(), 'status', 'status'),
                [
                    'prompt' => '--Silahkan Pilih--',
                ]
            )->label('Keterangan Status'); -->


            <!-- $form->field($model, 'promosikan_ke_beranda')->dropDownList(
                [
                    'Ya' => 'Ya',
                    'Tidak' => 'Tidak',

                ],
                ['prompt' => '--Silahkan Pilih--'],
            ); -->

        </div>
    </div>
    <div class="box box-success box-solid">
        <div class="box-header with-border">
            <b>Data Dokumen</b>

        </div>

        <div class="box-body">
            <?= $form->field($lampiran, 'judul_lampiran')->textInput(['placeholder' => 'tulis judul lampiran', 'maxlength' => true]) ?>

            <?= $form->field($lampiran, 'dokumen_lampiran')->widget(FileInput::classname(), [
                'pluginOptions' => ['allowedFileExtensions' => ['pdf'], 'showUpload' => false, 'showPreview' => false,],
            ]); ?>
            <?= $form->field($model, 'abstrak')->widget(FileInput::classname(), [
                'pluginOptions' => ['allowedFileExtensions' => ['pdf'], 'showUpload' => false, 'showPreview' => false,],
            ]); ?>
        </div>
    </div>



    <?= Html::submitButton('Simpan', ['class' => 'btn btn-success btn-flat']) ?>
    <?= \yii\helpers\Html::a('Batal', Yii::$app->request->referrer, ['class' => 'btn btn-danger btn-flat']); ?>

    <?php ActiveForm::end(); ?>

    <!-- /.tab-content -->
</div>
<!-- nav-tabs-custom -->





<?php
$script = <<< JS
$('#zipCode').change(function(){
    var zipId = $(this).val();
 
    $.get('get-peraturan',{ zipId : zipId },function(data){
        var data = $.parseJSON(data);
        $('#peraturan-singkatan_jenis').attr('value',data.singkatan);
        $('#peraturan-bentuk_peraturan').attr('value',data.name);
    });
});

$(function() {        
        $( "#tabs" ).tabs();
    });
JS;
$this->registerJs($script);
?>