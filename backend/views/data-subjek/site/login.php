<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

?>





<div class="login-box">
    <div class="login-logo">
        <center>
            <?php
            echo '<img class="img-responsive" src="' . Yii::$app->homeUrl . 'assets_b/img/logo-default.png"  alt="User Image">';
            ?>
        </center>
        <!--         <a href="#"><b>JDIH</b></a>
        <h6><p>Jaringan Dokumentasi dan Informasi Hukum</p></h6> -->
    </div>
    <!-- /.login-logo -->

    <div class="box box-warning direct-chat direct-chat-warning">



        <div class="login-box-body">

            <p class="login-box-msg">Silahkan Login</p>

            <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

            <?= $form
                ->field($model, 'username', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

            <div class="row">
                <div class="col-xs-8">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
                </div>
                <!-- /.col -->
            </div>


            <?php ActiveForm::end(); ?>


            <!-- /.social-auth-links -->

        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
    <!-- <h6><p><center>Badan Pengawas Pemilu</center></p></h6> -->

</div>