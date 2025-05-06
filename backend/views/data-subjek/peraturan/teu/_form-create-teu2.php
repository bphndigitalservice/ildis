<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
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
<?= $form->errorSummary([$model, $teu]); ?>
<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <b>Form Tambah Data Tajuk Entri Utama</b>
    </div>

    <div class="box-body">


        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($teu, 'jenis_pengarang')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\JenisPengarang::find()->asArray()->all(), 'id', 'name'),
            [
                'prompt' => '-- Silahkan Pilih --',
            ]
        )->label('Jenis Pengarang');
        ?>
        <?= $form->field($teu, 'tipe_pengarang')->dropDownList(
            \yii\helpers\ArrayHelper::map(\backend\models\TipePengarang::find()->asArray()->all(), 'id', 'name'),
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
    </div>
</div>

<?php ActiveForm::end(); ?>