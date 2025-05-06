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
                ],
            ]); ?>
    <div class="section">
          <div class="box box-primary box-solid">
            <div class="box-header with-border">
              <b>Detail Form Peraturan</b>
            </div>

            <div class="box-body">


        <?= $form->field($model, 'jenis_peraturan')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\JenisPeraturan::find()->where(['parent_id'=>1])->asArray()->all(), 'name', 'name'),['prompt'=>'-- Pilih Jenis Peraturan --'])->label('Jenis Peraturan'); 
        ?>
        <?= $form->field($model, 'singkatan_jenis')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'nomor_peraturan')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'tahun_terbit')->textInput(['maxlength' => 4])->label('Tahun') ?>
        <?= $form->field($model, 'judul')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'bidang_hukum')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\BidangHukum::find()->asArray()->all(), 'name', 'name'),
                ['prompt'=>'-- Pilih Bidang Hukum --'])->label('Bidang Hukum'); 
        ?>          
        <?= $form->field($model, 'teu')->textInput(['maxlength' => true])->label('T.E.U. Badan/Pengarang') ?>


        

        
      

        <?= $form->field($model, 'tempat_terbit')->textInput(['maxlength' => true])->label('Tempat Penetapan') ?>


        <?php  $form->field($model, 'penandatanganan')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tanggal_penetapan')->widget(DateControl::classname(), [
                'type' => 'date',
                'ajaxConversion' => true,
                'autoWidget' => true,
                'widgetClass' => '',
                'displayFormat' => 'php:d-F-Y',
                'saveFormat' => 'php:Y-m-d',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'php:d-F-Y'
                    ]
                ]])->label('Tanggal Penetapan'); 
        ?>        

        <?php /* $form->field($model, 'tanggal_pengundangan')->widget(DateControl::classname(), [
                'type' => 'date',
                'ajaxConversion' => true,
                'autoWidget' => true,
                'widgetClass' => '',
                'displayFormat' => 'php:d-F-Y',
                'saveFormat' => 'php:Y-m-d',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'php:d-F-Y'
                    ]
                ]])->label('Tanggal Pengundangan'); */
        ?> 
 

        <?= $form->field($model, 'sumber')->textarea(['placeholder'=>'contoh LN Nomor 21 Tahun 2017 ','rows' => 6]) ?>

       <?= $form->field($model, 'status')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\Status::find()->asArray()->all(), 'status', 'status'),
                [

                    'prompt'=>'-- Pilih Status Peraturan --',

            ])->label('Status Peraturan');        
        ?>
  

        <?= $form->field($model, 'bahasa')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\Bahasa::find()->asArray()->all(), 'name', 'name'),
                [
                    'prompt'=>'-- Pilih Bahasa --', 
                ])->label('Bahasa'); 
        ?>


        <?= $form->field($model, 'urusan_pemerintahan')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\UrusanPemerintahan::find()->asArray()->all(), 'name', 'name'),
                [
                    'prompt'=>'-- Pilih Urusan Pemerintahan --', 
                ])->label('Urusan Pemerintahan'); 
        ?>         
  
       

        <?php $form->field($model, 'abstrak')->textarea(['placeholder'=>'lihat permenkumham nomor 8 tahun 2019  ','rows' => 6]) ?>
                <?php // $form->field($model, 'katalog')->textarea(['placeholder'=>'lihat permenkumham nomor 8 tahun 2019  ','rows' => 6]) ?>
        <?php  //$form->field($model, 'lampiran')->widget(FileInput::classname(), [
                //'pluginOptions'=>['allowedFileExtensions'=>['pdf'],'showUpload' => false, 'showPreview' => false,],
                //])->label('File Peraturan'); 
        ?>  
    <div class="box-footer">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success btn-flat']) ?>
        <?= \yii\helpers\Html::a( 'Batal', Yii::$app->request->referrer,['class' => 'btn btn-danger btn-flat']);?>
    </div>
</div>
    <?php ActiveForm::end(); ?>
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