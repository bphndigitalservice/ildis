<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<section>
   <img src="frontend/assets/img/bluetop.jpg" class="img-fluid" alt="">
    <div class="container">
    </div>
    <div class="container">
        <div class="text-center"><br><br>
            <!-- <p><?= Html::a('Home', ['/']); ?></p> -->
            <p><span class="active">Login</span>
            </p>
        </div>
    </div>
    <br>
	<div class="container">
		<div class="row">
			<div class="col-lg-6 center-col">
				<div class="php-email-form"><br>
					<h3 class="text-center margin-40px-bottom  bg-primary text-white">Login</h3>
					<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

					<?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Username'])->label(false) ?>

					<?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false) ?>

					<div class="row">
						<div class="col-sm-6 mb-2">
						</div>
						<div class="col-sm-6 text-left text-sm-right">
						</div>

					</div>
					<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
				</div><br>
			</div>
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</section>