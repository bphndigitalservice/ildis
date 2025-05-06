<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */
/* @var $form yii\widgets\ActiveForm */


$jenis = \backend\models\JenisPeraturan::find()->where(['name' => $model->jenis_peraturan])->one();
//$listData = \yii\helpers\ArrayHelper::map(\backend\models\JenisPeraturan::find()->where(['name' => $model->bentuk_peraturan])->asArray()->all(), 'name', 'name');
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

<?= $form->errorSummary([$model]) ?>

<!-- Custom Tabs -->

<div class="tab-content">
    <div class="tab-pane active" id="tab_1">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <b>Form Data Utama Peraturan</b>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'jenis_peraturan')->dropDownList(
                    \yii\helpers\ArrayHelper::map(\backend\models\JenisPeraturan::find()->where(['parent_id' => 1])->asArray()->all(), 'name', 'name'),
                    [
                        'prompt' => 'Pilih Type Pengelolaan',
                        'onchange' => '
                                        $.get( "' . Url::toRoute('/peraturan/jenis2') . '", { id: $(this).val() } )
                                            .done(function( data ) {
                                                $( "#' . Html::getInputId($model, 'bentuk_peraturan') . '" ).html( data );

                                            }
                                        );'
                    ]
                )->label('Jenis Peraturan');
                ?>



                <?= $form->field($model, 'bentuk_peraturan')->dropDownList(
                    [$model->bentuk_peraturan => $model->bentuk_peraturan],
                    [
                        'prompt' => 'Pilih Bentuk Peraturan',
                    ]
                )->label('Bentuk Peraturan')
                ?>
                <?= $form->field($model, 'judul')->textarea(['placeholder' => 'tulis lengkap judul peraturan', 'rows' => 6]) ?>

                <?= $form->field($model, 'nomor_peraturan')->textInput(['placeholder' => 'tulis nomor peraturan', 'maxlength' => true]) ?>

                <?= $form->field($model, 'tahun_terbit')->textInput(['placeholder' => 'tulis tahun peraturan', 'maxlength' => 4])->label('Tahun') ?>


                <?=
                $form->field($model, 'tempat_terbit')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Daerah::find()->asArray()->all(), 'nama', 'nama'),
                    'options' => ['placeholder' => 'Pilih Tempat Penetapan'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label('Tempat Penetapan');;
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

                <?= $form->field($model, 'tanggal_pengundangan')->widget(
                    DateControl::classname(),
                    [
                        'type' => 'date',
                        'ajaxConversion' => true,
                        'autoWidget' => true,
                        'widgetClass' => '',
                        'displayFormat' => 'php:d-F-Y',
                        'saveFormat' => 'php:Y-m-d',
                        'widgetOptions' => [
                            'options' => ['placeholder' => 'tulis tanggal pengundangan'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'php:d-F-Y'
                            ]
                        ]
                    ]
                )->label('Tanggal Pengundangan');
                ?>
                <?= $form->field($model, 'pemrakarsa')->textInput(['placeholder' => 'tulis pemrakarsa', 'maxlength' => true]) ?>
                <?= $form->field($model, 'sumber')->textarea(['placeholder' => 'contoh LN Nomor 21 Tahun 2017 ', 'rows' => 6]) ?>

                <?= $form->field($model, 'bahasa')->dropDownList(
                    \yii\helpers\ArrayHelper::map(\backend\models\Bahasa::find()->asArray()->all(), 'name', 'name'),
                    [
                        'prompt' => '--Silahkan Pilih--',
                    ]
                )->label('Bahasa');
                ?>

                <?= $form->field($model, 'urusan_pemerintahan')->dropDownList(
                    \yii\helpers\ArrayHelper::map(\backend\models\UrusanPemerintahan::find()->asArray()->all(), 'name', 'name'),
                    [
                        'prompt' => '---Silahkan Pilih--',
                    ]
                )->label('Urusan Pemerintahan');
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
                ]); ?>

                <?= $form->field($model, 'status')->dropDownList(
                    ['Berlaku' => 'Berlaku', 'Tidak Berlaku' => 'Tidak Berlaku'],
                    [
                        'prompt' => '--Silahkan Pilih--',
                    ]
                )->label('Status Peraturan');
                ?>

            </div>
        </div>
    </div>




    <div class="box-footer">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success btn-flat']) ?>
        <?= \yii\helpers\Html::a('Batal', Yii::$app->request->referrer, ['class' => 'btn btn-danger btn-flat']); ?>
    </div>
</div>
<div class="section">
    <?php ActiveForm::end(); ?>



    <?php
    $script = <<< JS
$('#zipCode').change(function(){
    var zipId = $(this).val();
 
    $.get('../get-peraturan',{ zipId : zipId },function(data){
        var data = $.parseJSON(data);
        $('#peraturan-singkatan_jenis').attr('value',data.singkatan);
        $('#peraturan-bentuk_peraturan').attr('value',data.name);
    });
});
JS;
    $this->registerJs($script);
    ?>