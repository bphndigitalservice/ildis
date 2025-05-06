<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Program */
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
]);
?>
<?= $form->errorSummary($model); ?>
<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <b>Form Tambah Data Tajuk Entri Utama</b>
    </div>

    <div class="box-body">
        <?php
        /*
            $form->field($model, 'nama_pengarang')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Pengarang::find()->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Pilih Pengarang'],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ])->label('Nama Pengarang')->hint('Bila pengarang/TEU tidak ada silahkan klik tombol Tambah TEU Baru')
        */
        ?>

        <?=  $form->field($model, 'nama_pengarang')->widget(Select2::classname(), [
           
                'options' => [

                    'placeholder' => 'Pilih Peraturan...',
                    //'onchange' =>'return checkStatus(this.value)',
                //'onchange' =>'userAlert();'
                ],
                
                //'onchange'=>'return checkStatus(this.value)', //option

                'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 2,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => Url::to(['/peraturan/loadteu']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],


                ],
            ]);
        ?>
        <?= $form->field($model, 'tipe_pengarang')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\TipePengarang::find()->asArray()->all(), 'id', 'name'),
            [
                'prompt' => '-- Silahkan Pilih --',
            ]
        )->label('Type Pengarang');
        ?>

        <?= $form->field($model, 'jenis_pengarang')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\JenisPengarang::find()->asArray()->all(), 'id', 'name'),
            [
                'prompt' => '-- Silahkan Pilih --',
            ]
        )->label('Jenis Pengarang');
        ?>

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
        <?= Html::a(Yii::t('app', '<i class="fa fa-plus-circle"></i>  Tambah TEU Baru'), ['tambah-pengarang2', 'id' => $id], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>