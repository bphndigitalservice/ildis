
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
<?php
NavBar::begin([
	'brandLabel' => 'NavBar Test',
'innerContainerOptions' => ['class' => 'container'],
'options' => ['class' => 'navbar navbar-default'],

	]);
echo Nav::widget([
    'items' => [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
    ],
    'options' => ['class' => 'nav navbar-nav'],
]);
NavBar::end();
?>
	</header><!-- header -->
  	<?= $content ?>
	<!-- footer -->
	<?= $this->render('footer.php') ?>

    <?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
