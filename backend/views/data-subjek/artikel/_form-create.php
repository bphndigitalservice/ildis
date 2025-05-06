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

<?= $form->errorSummary([$model, $lampiran]) ?>



<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <b>Form Data Utama Artikel</b>
    </div>
    <div class="box-body">
        <?= $form->field($model, 'jenis_peraturan')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\JenisPeraturan::find()->where(['parent_id' => 3])->asArray()->all(), 'name', 'name'),
            [
                'id' => 'zipCode',
                'prompt' => '-- Pilih Jenis Artikel --',
            ]
        )->label('Jenis Artikel');
        ?>

        <?= $form->field($model, 'judul')->textarea(['placeholder' => 'tulis lengkap judul artikel', 'rows' => 6]) ?>


        <?= $form->field($model, 'tahun_terbit')->textInput(['placeholder' => 'tulis tahun artikel', 'maxlength' => 4])->label('Tahun') ?>


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
                    'options' => ['placeholder' => 'tulis tanggal artikel '],
                    'pluginOptions' => [

                        'autoclose' => true,
                        'format' => 'php:d-F-Y'
                    ]
                ]
            ]
        )->label('Tanggal Artikel');
        ?>



        <?= $form->field($model, 'sumber')->textarea(['placeholder' => 'contoh LN Nomor 21 Tahun 2017 ', 'rows' => 6]) ?>

        <?= $form->field($model, 'bahasa')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\Bahasa::find()->asArray()->all(), 'name', 'name'),
            [
                'prompt' => '--Silahkan Pilih--',
            ]
        )->label('Bahasa');
        ?>

        <?= $form->field($model, 'bidang_hukum')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\BidangHukum::find()->asArray()->all(), 'name', 'name'),
            [
                'prompt' => '--Silahkan Pilih--',
            ]
        )->label('Bidang Hukum');
        ?>






        <?= $form->field($model, 'tipe_dokumen')->hiddenInput(['value' => 3])->label(false); ?>
    </div>
</div>

<div class="box box-success box-solid">
    <div class="box-header with-border">
        <b>Lampiran</b>
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

<div class="box-footer">
    <?= Html::submitButton('Simpan', ['class' => 'btn btn-success btn-flat']) ?>
    <?= \yii\helpers\Html::a('Batal', Yii::$app->request->referrer, ['class' => 'btn btn-danger btn-flat']); ?>
</div>

<?php ActiveForm::end(); ?>



<!-- $script = <<< JS
$('#zipCode').change(function(){
    var zipId = $(this).val();
 
    $.get('get-peraturan',{ zipId : zipId },function(data){
        var data = $.parseJSON(data);
        $('#artikel-singkatan_jenis').attr('value',data.singkatan);
        $('#artikel-bentuk_peraturan').attr('value',data.name);
    });
});
JS;
$this->registerJs($script); -->