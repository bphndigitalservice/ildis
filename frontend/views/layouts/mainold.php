
<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
use yii\widgets\Menu;
AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
	    <meta charset="<?= Yii::$app->charset ?>">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <?= Html::csrfMetaTags() ?>
	    <title><?= Html::encode($this->title) ?></title>
	    <?php $this->head() ?>
    </head>


  	<body>
    <?php $this->beginBody() ?> 
	<!-- header -->
	<header id="header" class="clearfix">
		<!-- navbar -->
		<nav class="navbar navbar-default">
			<div class="container">
				<!-- navbar-header -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
				
					<?= Html::a(Html::img('@web/images/logo2.png',['class'=>'img-responsive']), ['/'], ['class'=>'navbar-brand']);?>
				</div>
				<!-- /navbar-header -->
				
				<div class="navbar-left">
					<div class="collapse navbar-collapse" id="navbar-collapse">							
						<?php         
						    $menuItems = [
						        [
						            'label' => 'Home', 
						            'url' => ['/site/index'],					          
						        ],
						        ['label' => 'Rancangan', 'url' => ['/rancangan/index']],					  			
						        ['label' => 'Kontak', 'url' => ['/site/kontak']],           
						    ];	
						    echo Menu::widget([
						        'items' => $menuItems,					       					   
						        'activateItems' => true,
						        'options' => [
						            'class' => 'nav navbar-nav',
						        ],					        
						        'activateParents' => true,
						        'encodeLabels' => false, 					            
						    ]);					    
						?>						
					</div>
				</div><!-- navbar-left -->
				
				<!-- nav-right -->
				<div class="nav-right">			
					<?php
					if (Yii::$app->user->isGuest) {
	        			$menuright[] = ['label' => '<i class="fa fa-user"></i> Login', 'url' => ['/site/login']];
	        			$menuright[] = ['label' => 'Register', 'url' => ['/site/signup']];
	    			} else {
	         			$menuright[] = ['label' => 'Sign out', 'url' => ['/site/logout'],  ['data' => ['method' => 'post']]];
	          			$menuright[] = ['label' => 'Profile', 'url' => ['/site/profile']];
    				}		
					echo Menu::widget([
					        'items' => $menuright,
					        'activateItems' => true,
					        'options' => [
					            'class' => 'sign-in',
					        ],		
					         'activateParents' => true,
					        'encodeLabels' => false, 			        					            
					    ]);					    
					?>	

									
				</div>
				<!-- nav-right -->
			</div><!-- container -->
		</nav><!-- navbar -->
	</header><!-- header -->
  	<?= $content ?>
	<!-- footer -->
	<?= $this->render('footer.php') ?>

    <?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
