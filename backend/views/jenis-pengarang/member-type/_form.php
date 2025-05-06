<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */
/* @var $model backend\models\MemberType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-type-form box box-primary">
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
    <div class="box-body table-responsive">
          <div class="box box-primary box-solid">
            <div class="box-header with-border">
              <b>member-type</b>
            </div>

            <div class="box-body">
        <?= $form->field($model, 'member_type_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'loan_limit')->textInput() ?>

        <?= $form->field($model, 'loan_periode')->textInput() ?>

        <?= $form->field($model, 'enable_reserve')->textInput() ?>

        <?= $form->field($model, 'reserve_limit')->textInput() ?>

        <?= $form->field($model, 'member_periode')->textInput() ?>

        <?= $form->field($model, 'reborrow_limit')->textInput() ?>

        <?= $form->field($model, 'fine_each_day')->textInput() ?>

        <?= $form->field($model, 'grace_periode')->textInput() ?>

        <?= $form->field($model, 'input_date')->textInput() ?>

        <?= $form->field($model, 'last_update')->textInput() ?>

        <?= $form->field($model, 'id_tipe_koleksi')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_tipe_gmd')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'created_by')->textInput() ?>

        <?= $form->field($model, 'updated_by')->textInput() ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'updated_at')->textInput() ?>

    </div>
</div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
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