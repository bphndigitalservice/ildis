<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="page-title-section bg-img cover-background" data-overlay-dark="7" data-background="/jdih/img/banner/header.jpg">
    <div class="container">
        <h1>Lupa Password</h1>
        <ul class="text-center">
            <li>
                <a href="index.html">Home</a>
            </li>
            <li>
                <span class="active">Login</span>
            </li>
        </ul>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">

            <div class="site-request-password-reset">
                <h1><?= Html::encode($this->title) ?></h1>

                <p>Please fill out your email. A link to reset password will be sent there.</p>

                <div class="row">
                    <div class="col-lg-12">
                        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>