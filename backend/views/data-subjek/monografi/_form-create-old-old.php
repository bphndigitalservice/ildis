<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */
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
    ]
]);
?>

<?= $form->errorSummary([$model, $teu]) ?>

<!-- Custom Tabs -->
<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <b>Monografi Hukum</b>
    </div>
    <div class="box-body">
        <?= $form->field($model, 'jenis_peraturan')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\JenisPeraturan::find()->where(['parent_id' => 2])->asArray()->all(), 'name', 'name'),
            [
                'id' => 'zipCode',
                'prompt' => '-- Pilih Jenis Monografi --',
            ]
        )->label('Jenis Monografi');
        ?>

        <?= $form->field($model, 'judul')->textarea(['placeholder' => 'tulis lengkap judul peraturan', 'rows' => 6]) ?>

        <?= $form->field($model, 'tahun_terbit')->textInput(['placeholder' => 'tulis tahun peraturan', 'maxlength' => 4])->label('Tahun Terbit') ?>

        <?= $form->field($model, 'penerbit')->textInput(['placeholder' => 'tulis penerbit', 'maxlength' => true])->label('penerbit') ?>


        <?=

            $form->field($model, 'tempat_terbit')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Daerah::find()->asArray()->all(), 'nama', 'nama'),
                'options' => ['placeholder' => 'Pilih Tempat Terbit'],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]);
        ?>
        <?= $form->field($model, 'nomor_panggil')->textInput(['placeholder' => 'tulis nomor panggil', 'maxlength' => true])->label('Nomor Panggil') ?>

        <?= $form->field($model, 'deskripsi_fisik')->textInput(['placeholder' => 'tulis deskripsi Fisik', 'maxlength' => true])->label('Deskripsi Fisik') ?>

        <?= $form->field($model, 'klasifikasi')->textInput(['placeholder' => 'tulis klasifikasi', 'maxlength' => true])->label('Klasifikasi') ?>
        <?= $form->field($model, 'isbn')->textInput(['placeholder' => 'tulis isbn', 'maxlength' => true])->label('ISBN') ?>

        <?= $form->field($model, 'sumber')->textarea(['placeholder' => 'tulis anotasi ', 'rows' => 6])->label('Anotasi') ?>

        <?= $form->field($model, 'bahasa')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\Bahasa::find()->asArray()->all(), 'name', 'name'),
            [
                'prompt' => '--Silahkan Pilih--',
            ]
        )->label('Bahasa');
        ?>

        <?= $form->field($model, 'sumber_perolehan')->dropDownList(
            [
                'Beli' => 'Beli',
                'Hibah' => 'Hibah',
            ],
            ['prompt' => '--Silahkan Pilih--'],
        );
        ?>
        <?= $form->field($model, 'bidang_hukum')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\BidangHukum::find()->asArray()->all(), 'name', 'name'),
            [
                'prompt' => '--Silahkan Pilih--',
            ]
        )->label('Bidang Hukum');
        ?>


        <?= $form->field($model, 'abstrak')->widget(FileInput::classname(), [
            'pluginOptions' => ['allowedFileExtensions' => ['pdf'], 'showUpload' => false, 'showPreview' => false,],
        ]);
        ?>

        <?= $form->field($model, 'gambar_sampul')->widget(FileInput::classname(), [
            'pluginOptions' => ['allowedFileExtensions' => ['png', 'jpg', 'jpeg', 'bmp'], 'showUpload' => false, 'showPreview' => false,],
        ]);
        ?>


        <?= $form->field($model, 'singkatan_jenis')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'bentuk_peraturan')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'tipe_dokumen')->hiddenInput(['value' => 2])->label(false); ?>
    </div>
</div>

<!-- /.tab-pane -->

<!-- /.tab-pane -->

<div class="box box-success box-solid">
    <div class="box-header with-border">
        <b>Tajuk Entry Utama</b>
    </div>
    <div class="box-body">
        <?= $form->field($teu, 'nama_pengarang', ['inputOptions' => ['autofocus' => 'autofocus']])->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\backend\models\Pengarang::find()->asArray()->all(), 'id', 'name'),
            'options' => ['placeholder' => 'Pilih Tajuk Entry Utama'],
            'pluginOptions' => [
                'allowClear' => true
            ]
        ])->label('Nama Pengarang');
        ?>

        <?= $form->field($teu, 'tipe_pengarang')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\TipePengarang::find()->asArray()->all(), 'id', 'name'),
            [
                'prompt' => '-- Silahkan Pilih --',
            ]
        )->label('Type Pengarang');
        ?>

        <?= $form->field($teu, 'jenis_pengarang')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\JenisPengarang::find()->asArray()->all(), 'id', 'name'),
            [
                'prompt' => '-- Silahkan Pilih --',
            ]
        )->label('Jenis Pengarang');
        ?>
    </div>
</div>







<!-- 
    $form->field($model, 'direktorat_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(\backend\models\Unit::find()->where(['parent_id'=>NULL])->asArray()->all(), 'id', 'nama_unit'),
        ['prompt'=>'-- Pilih Direktorat --',
                'onchange'=>'
                        $.get( "'.Url::toRoute('/peminjaman/subdit').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.Html::getInputId($model, 'subdit_id').'" ).html( data );
                            }
                        );
                    '  
    ])->label('Dari');
    

 
    $form->field($model, 'penanggungjawab')->widget(\kartik\widgets\Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Penangggungjawab::find()->asArray()->all(), 'nama', 'nama'),
    'options' => ['placeholder' => 'Pilih Pejabat Penanggung Jawab'],
    'pluginOptions' => [
        'allowClear' => true
    ]]);*


    $form->field($model, 'file')->widget(FileInput::classname(), [
       'pluginOptions'=>['allowedFileExtensions'=>['pdf'],'showUpload' => false,],
    ]); 

    $form->field($model, 'tanggal_nodin')->widget(DateControl::classname(), [
    'type' => 'date',
    'ajaxConversion' => true,
    'autoWidget' => true,
    'widgetClass' => '',
    'displayFormat' => 'php:d-F-Y',
    'saveFormat' => 'php:Y-m-d',
    // 'saveTimezone' => 'Asia/Bangkok',
    // 'displayTimezone' => 'Asia/Bangkok',

    'widgetOptions' => [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'php:d-F-Y'
        ]
    ]])->label('Tanggal Nota Dinas');*/
-->

<div class="box-footer">
    <?= Html::submitButton('Simpan', ['class' => 'btn btn-success btn-flat']) ?>
    <?= \yii\helpers\Html::a('Batal', Yii::$app->request->referrer, ['class' => 'btn btn-danger btn-flat']); ?>
</div>

<?php ActiveForm::end(); ?>



<?php
$script = <<< JS
$('#zipCode').change(function(){
    var zipId = $(this).val();
 
    $.get('get-peraturan',{ zipId : zipId },function(data){
        var data = $.parseJSON(data);
        $('#monografi-singkatan_jenis').attr('value',data.singkatan);
        $('#monografi-bentuk_peraturan').attr('value',data.name);
    });
});
JS;
$this->registerJs($script);
?>