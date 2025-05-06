<?php
$dir = 'assets/';
array_map('unlink', glob("{$dir}*.*"));

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
use yii\widgets\Menu;

AppAsset::register($this);

use backend\models\FrontendConfig;

$logo = FrontendConfig::findOne(1);

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
    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <?php $this->head() ?>
    
</head>


<body>

    <?php $this->beginBody() ?>

    <!-- start main-wrapper section -->

    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex justify-content-between align-items-center">

          <div class="logo">
             <?= Html::a(Html::img('@web/common/dokumen/' . $logo->isi_konfig, ['id' => 'logo']), ['/'], ['class' => 'navbar-brand width-200px sm-width-180px xs-width-150px']); ?>
            <h1><a href=""></a></h1>
            <!-- Uncomment below if you prefer to use an image logo -->
            <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
          </div>

          <nav id="navbar" class="navbar">
            <?= $this->render('menu.php') ?>
	    <i class="bi bi-list mobile-nav-toggle" style="color:blue"></i>
          </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <?= $content ?>

    </main><!-- End #main -->

        <?= $this->render('footer.php') ?>

    <!-- end main-wrapper section -->

    <!-- start scroll to top -->
    <a href="javascript:void(0)" class="scroll-to-top"><i class="fas fa-angle-up" aria-hidden="true"></i></a>
    <!-- end scroll to top -->

    <!-- all js include start -->

    <!-- jQuery -->


    <!-- all js include end -->

    <?php $this->endBody() ?>

    <!-- Google Analytics Start -->



    <!-- Google Analytics End -->

</body>

</html>
<?php $this->endPage() ?>
