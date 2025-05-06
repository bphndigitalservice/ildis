<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */
/* @var $model backend\models\Eksemplar */
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
    
          <div class="box box-primary box-solid">
            <div class="box-header with-border">
              <b>Form Tambah Data Eksemplar</b>
            
            </div>

            <div class="box-body">
                <?= $form->field($model, 'id_dokumen')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'kode_eksemplar')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'no_panggil')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'kode_inventaris')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'id_lokasi')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'lokasi_rak')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'tipe_lokasi')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'status_eksemplar')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'nomor_pemesanan')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'tgl_pemesanan')->textInput() ?>

<?= $form->field($model, 'tgl_penerimaan')->textInput() ?>

<?= $form->field($model, 'agen')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'sumber_perolehan')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'faktur')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'tgl_faktur')->textInput() ?>

<?= $form->field($model, 'harga')->textInput() ?>

<?= $form->field($model, 'created_at')->textInput() ?>

<?= $form->field($model, 'created_by')->textInput() ?>

<?= $form->field($model, 'updated_at')->textInput() ?>

<?= $form->field($model, 'updated_by')->textInput() ?>

            </div>
            <div class="box-footer">
                <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', 
                    [
                        'class' => 'btn btn-success btn-flat',
                        'data' => [
                                    'confirm' => 'Yakin data sudah benar.', 
                                    'method' => 'post', 
                                    'data-pjax' => false],
                    ]) ?>
                <?= \yii\helpers\Html::a('<i class="fa fa-remove"></i> Batal', Yii::$app->request->referrer,['class' => 'btn btn-danger btn-flat'])?>                
            </div>
        </div>
    
    <?php ActiveForm::end(); ?>


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