<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use kartik\editors\Summernote;


// $js = <<<JS
// $("#summernote").summernote({
//     height: 300,
//     toolbar: [
//     [ 'style', [ 'style' ] ],
//     [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
//     [ 'fontname', [ 'fontname' ] ],
//     [ 'fontsize', [ 'fontsize' ] ],
//     [ 'color', [ 'color' ] ],
//     [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
//     [ 'table', [ 'table' ] ],
//     [ 'insert', [ 'link'] ],
//     [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
//     ]
//     });
//     JS;
//     $this->registerJs($js);
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



        <?= $form->field($model, 'nama_konfig')->textInput(['maxlength' => true]) ?>


        <?=
        $form->field($model, 'isi_konfig')->widget(Summernote::class, [
            'options' => ['placeholder' => 'masukkan isi berita...']
        ]);
        ?>

        <?php
            // $form->field($model, 'image')->widget(FileInput::classname(), [
            //     'pluginOptions' => ['allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'bmp'], 'showUpload' => false,],
            // ])
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

