<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use yii\jui\DatePicker;
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
    <?= $form->errorSummary([$model]) ?>

    <div class="box box-success box-solid">
        <div class="box-header with-border">
            <b>Form Dokumen Abstrak</b>

        </div>

        <div class="box-body">

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