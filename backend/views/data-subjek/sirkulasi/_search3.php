<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-search">

    <?php $form = ActiveForm::begin([
        'action' => ['pengembalian'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>


    <!-- $form->field($model, 'id', ['inputOptions' => ['autofocus' => 'autofocus']])->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Member::find()->asArray()->all(), 'id', 'username'),
        'options' => ['placeholder' => 'Pilih Member'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ])->label('Nama Member'); -->


    <?php
    /* @var $searchModel app\models\UserSearch */
    echo $form->field($model, 'item_code', [
        'template' => '<div class="input-group">{input}<span class="input-group-btn">' .
            Html::submitButton('Cari Buku', ['class' => 'btn btn-primary']) .
            '</span></div>',
    ])->textInput(['placeholder' => 'Search']);
    ?>

    <?php ActiveForm::end(); ?>

</div>