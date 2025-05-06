<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="row">
        <div class="login-box">
            <div class="col-md-12 box box-radius">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username', ['template' => '
                        <div class="col-sm-12" style="margin-top:15px;">
                            <div class="input-group col-sm-12">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-user"></span>
                                </span>
                                {input}
                            </div>{error}{hint}
                        </div>'])->textInput(['autofocus' => true])
                    ->input('text', ['placeholder' => 'Username']) ?>

                <?= $form->field($model, 'password', ['template' => '
                        <div class="col-sm-12" style="margin-top:15px;">
                            <div class="input-group col-sm-12">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-lock"></span>
                                </span>
                                {input}
                            </div>{error}{hint}
                        </div>'])->passwordInput()
                    ->input('password', ['placeholder' => 'Password']) ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>