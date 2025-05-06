<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pola-eksemplar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['pengembalian'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <?= $form->field($model, 'title')->label('Judul Buku') ?>
    <?= $form->field($model, 'item_code')->label('Kode Eksemplar') ?>
    <?= $form->field($model, 'member')->label('Member') ?>



    <?php // echo $form->field($model, 'status') 
    ?>

    <?php // echo $form->field($model, 'created_by') 
    ?>

    <?php // echo $form->field($model, 'updated_by') 
    ?>

    <?php // echo $form->field($model, 'created_at') 
    ?>

    <?php // echo $form->field($model, 'updated_at') 
    ?>

    <div class="form-group">
        <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>