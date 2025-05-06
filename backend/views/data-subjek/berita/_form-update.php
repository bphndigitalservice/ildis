<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use kartik\editors\Summernote;
/* @var $this yii\web\View */
/* @var $model backend\models\Berita */
/* @var $form yii\widgets\ActiveForm */

$js = <<<JS
$("#summernote").summernote({
        height: 300,
        toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
            [ 'fontname', [ 'fontname' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'color', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            [ 'table', [ 'table' ] ],
            [ 'insert', [ 'link'] ],
            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
        ]
    });
JS;
$this->registerJs($js);
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
        <b>Form Tambah Data Berita</b>

    </div>

    <div class="box-body">

        <?= $form->field($model, 'tanggal')->widget(
            DateControl::classname(),
            [
                'type' => 'date',
                'ajaxConversion' => true,
                'autoWidget' => true,
                'widgetClass' => '',
                'displayFormat' => 'php:d-F-Y',
                'saveFormat' => 'php:Y-m-d',
                'widgetOptions' => [
                    'options' => ['placeholder' => 'tulis tanggal berita'],
                    'pluginOptions' => [

                        'autoclose' => true,
                        'format' => 'php:d-F-Y'
                    ]
                ]
            ]
        )
        ?>

        <?= $form->field($model, 'judul')->textInput(['maxlength' => true]) ?>


        <?=
            $form->field($model, 'isi')->widget(Summernote::class, [
                'options' => ['placeholder' => 'masukkan isi berita...']
            ]);
        ?>

        <?=
            $form->field($model, 'image')->widget(FileInput::classname(), [
                'pluginOptions' => ['allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'bmp'], 'showUpload' => false,],
            ])
        ?>



        <?=
            $form->field($model, 'status')->dropDownList(
                ['1' => 'Publish', '1' => 'Tidak Publish'],
                ['prompt' => 'Pilih Status...']
            );
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
        ) ?>
        <?= \yii\helpers\Html::a('<i class="fa fa-remove"></i> Batal', Yii::$app->request->referrer, ['class' => 'btn btn-danger btn-flat']) ?>
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