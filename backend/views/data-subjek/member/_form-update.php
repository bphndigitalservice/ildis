<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */
/* @var $model backend\models\Member */
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
<?= $form->errorSummary([$model]) ?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Data Login</a></li>
        <li><a href="#tab_2" data-toggle="tab">Profil</a></li>

    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">

            <?= $form->field($model, 'username')->textInput(['placeholder' => 'username huruf kecil', 'maxlength' => true]) ?>

            <?= $form->field($model, 'member_email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'member_since_date')->widget(
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
            )
            ?>

            <?= $form->field($model, 'register_date')->widget(
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
            )
            ?>

            <?= $form->field($model, 'expire_date')->widget(
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
            )
            ?>




        </div>
        <div class="tab-pane" id="tab_2">
            <?= $form->field($model, 'member_name')->textInput(['maxlength' => true]) ?>
                   <?= $form->field($model, 'personal_id_number')->textInput(['maxlength' => true])->label('Personal Number') ?>

            <?=
                $form->field($model, 'gender')->dropDownList(
                    ['Pria' => 'Pria', 'Wanita' => 'Wanita'],
                    ['prompt' => 'Pilih Gender...']
                );
            ?>
            <?= $form->field($model, 'birth_date')->widget(
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
            )
            ?>



            <?= $form->field($model, 'member_type_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\backend\models\MemberType::find()->asArray()->all(), 'id', 'member_type_name'),
                [
                    'prompt' => '--Silahkan Pilih--',
                ]
            )
            ?>

            <?= $form->field($model, 'member_address')->textarea(['rows' => 6]) ?>



            <?= $form->field($model, 'postal_code')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'personal_id_number')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'inst_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'fax_number')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'member_notes')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'member_image')->widget(FileInput::classname(), [
                'pluginOptions' => ['allowedFileExtensions' => ['bmp', 'png', 'jpg', 'jpeg'], 'showUpload' => false, 'showPreview' => false,],
            ]);
            ?>
        </div>
    </div>
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


<?php ActiveForm::end(); ?>