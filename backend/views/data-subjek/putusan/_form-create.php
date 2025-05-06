<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
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

    <!-- Custom Tabs -->
    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <b>Form Data Utama</b>
        </div>

        <div class="box-body">
            <?= $form->field($model, 'jenis_peraturan')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\JenisPeraturan::find()->where(['parent_id' => 4])->asArray()->all(), 'name', 'name'),
                [
                    'id' => 'zipCode',
                    'prompt' => '-- Pilih Jenis Putusan --',
                ]
            )->label('Jenis Putusan');
            ?>

            <?= $form->field($model, 'judul')->textarea(['placeholder' => 'tulis lengkap judul putusan', 'rows' => 6])->label('Judul Putusan') ?>

            <?= $form->field($model, 'nomor_peraturan')->textInput(['placeholder' => 'tulis nomor peraturan', 'maxlength' => true])->label('Nomor Putusan') ?>

            <?= $form->field($model, 'pemohon')->textInput(['placeholder' => 'tulis nama pemohon', 'maxlength' => true])->label('Pemohon') ?>

            <?= $form->field($model, 'termohon')->textInput(['placeholder' => 'tulis nama termohon', 'maxlength' => true])->label('Termohon') ?>

            <?= $form->field($model, 'lembaga_peradilan')->textInput(['placeholder' => 'tulis tempat pengadilan', 'maxlength' => true])->label('Tempat Pengadilan') ?>

            <?= $form->field($model, 'tahun_terbit')->textInput(['placeholder' => 'tulis tahun peraturan', 'maxlength' => 4])->label('Tahun') ?>

            <?= $form->field($model, 'tempat_terbit')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Daerah::find()->asArray()->all(), 'nama', 'nama'),
                'options' => ['placeholder' => 'Pilih Tempat Terbit'],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]);
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

            <?= $form->field($model, 'klasifikasi')->textarea(['placeholder' => 'contoh LN Nomor 21 Tahun 2017 ', 'rows' => 6]) ?>

            <?= $form->field($model, 'bahasa')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\Bahasa::find()->asArray()->all(), 'name', 'name'),
                [
                    'prompt' => '--Silahkan Pilih--',
                ]
            )->label('Bahasa');
            ?>

<?php
echo $form->field($model, 'sub_klasifikasi')->dropDownList(
            ['Perama' => 'Pertama', 'Banding' => 'Banding', 'Kasasi' => 'Kasasi','Peninjauan Kembali'=>'Peninjauan Kembali'],['prompt'=>'--Silahkan Pilih--']
    )->label('Tingkat Kasasi'); ?>

            <?= $form->field($model, 'bidang_hukum')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\BidangHukum::find()->asArray()->all(), 'name', 'name'),
                [
                    'prompt' => '--Silahkan Pilih--',
                ]
            )->label('Bidang Hukum');
            ?>

            <?= $form->field($model, 'amar_status')->textInput(['placeholder' => 'tulis amar Putusan', 'maxlength' => true])->label('Amar Putusan') ?>

            <?= $form->field($model, 'singkatan_jenis')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'bentuk_peraturan')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'tipe_dokumen')->hiddenInput(['value' => 4])->label(false); ?>
        </div>
    </div>

    <div class="box box-danger box-solid">
        <div class="box-header with-border">
            <b>Form Data Dokumen</b>
        </div>

        <div class="box-body">

            <?= $form->field($lampiran, 'judul_lampiran')->textInput(['placeholder' => 'tulis penandatangan peraturan', 'maxlength' => true]) ?>

            <?= $form->field($lampiran, 'dokumen_lampiran')->widget(FileInput::classname(), [
                'pluginOptions' => ['allowedFileExtensions' => ['pdf'], 'showUpload' => false, 'showPreview' => false,],
            ]); ?>

            <?= $form->field($model, 'abstrak')->widget(FileInput::classname(), [
                'pluginOptions' => ['allowedFileExtensions' => ['pdf'], 'showUpload' => false, 'showPreview' => false,],
            ]); ?>

        </div>
    </div>
    <!-- /.tab-content -->
</div>
<!-- nav-tabs-custom -->

<div class="section">



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
        $('#putusan-singkatan_jenis').attr('value',data.singkatan);
        $('#putusan-bentuk_peraturan').attr('value',data.name);
    });
});
JS;
$this->registerJs($script);
?>