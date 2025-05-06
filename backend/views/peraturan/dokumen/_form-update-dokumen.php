<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\DataLampiran;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */

$dokumens  = \backend\models\DataLampiran::find()->all();
foreach ($dokumens as $dokumen) {
    $dokumen->fulltext = $dokumen->peraturan->judul;
}

$activities = \yii\helpers\ArrayHelper::map($dokumens, 'dokumen_lampiran', 'fulltext');
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
        <b>Form Ubah Dokumen Terkait</b>
    </div>

    <div class="box-body">


        <?php
        // $form->field($model, 'document_terkait')->widget(\kartik\widgets\Select2::classname(), [
        //     'data' => $activities,
        //         // 'data' => ArrayHelper::map(
        //         //     DataLampiran::find()->asArray()->all(),
        //         //     'id',
        //         //     function ($model) {
        //         //         return $model->getPeraturan->judul . ')';
        //         //     }
        //         // ),
        //     'options' => ['placeholder' => 'Pilih Peraturan'],
        //     'pluginOptions' => [
        //         'allowClear' => true
        //     ]
        // ])->label('Peraturan');
        ?>
        <?= $form->field($model, 'kloning')->widget(Select2::classname(), [

            'options' => [
                'placeholder' => 'Pilih Judul Artikel Hukum...',
            ],

            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 2,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => Url::to(['dokumenonly']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],


            ],
        ])->label('Pilih Artikel Hukum')->hint('* Dokumen disini adalah dokumen yang terdapat dalam artikel hukum');
        ?>
        <?= $form->field($model, 'status_docter')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'catatan_docter')->textarea(['placeholder' => 'isi catatan', 'rows' => 6]) ?>




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