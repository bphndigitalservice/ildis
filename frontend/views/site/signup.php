<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
	<section class="job-bg user-page">
		<div class="container">
			<div class="row text-center">
				<!-- user-login -->			
				<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
					<div class="user-account job-user-account">
						<h2>Create An Account</h2>


							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="find-job">


            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' =>'username'])->label(false) ?>

                <?= $form->field($model, 'email')->textInput(['placeholder' =>'email'])->label(false) ?>

                <?= $form->field($model, 'password')->passwordInput(['placeholder' =>'password'])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>									
								</div>

							</div>				
					</div>
				</div><!-- user-login -->			
			</div><!-- row -->	
		</div><!-- container -->
	</section><!-- signup-page -->