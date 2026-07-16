<?php

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
$siteName = 'JDIH - Jaringan Dokumentasi dan Informasi Hukum';
$canonicalUrl = Url::canonical();

if (empty($this->params['description'])) {
    $this->registerMetaTag(['name' => 'description', 'content' => 'Jaringan Dokumentasi dan Informasi Hukum - Portal hukum terlengkap untuk peraturan, monografi, putusan, dan artikel hukum.']);
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - <?= Html::encode($siteName) ?></title>
    <link rel="canonical" href="<?= Html::encode($canonicalUrl) ?>" />
    <?php $this->head() ?>

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?= $this->render('_google_analytics') ?>

</head>


<body>

    <?php $this->beginBody() ?>

    <!-- start main-wrapper section -->

    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex justify-content-between align-items-center">

            <div class="logo">
                <?= Html::a(Html::img('@web/common/dokumen/' . $logo->isi_konfig, ['id' => 'logo', 'alt' => Html::encode($siteName)]), ['/'], ['class' => 'navbar-brand width-200px sm-width-180px xs-width-150px']); ?>
            </div>

            <nav id="navbar" class="navbar">
                <?= $this->render('menu.php') ?>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <?= $content ?>

    </main><!-- End #main -->

    <?= $this->render('footer.php') ?>

    <!-- end main-wrapper section -->

    <!-- start scroll to top -->
    <a href="#" class="back-to-top" aria-label="Kembali ke atas"><i class="bi bi-chevron-up" aria-hidden="true"></i></a>
    <!-- end scroll to top -->

    <!-- all js include start -->

    <!-- jQuery -->


    <!-- all js include end -->

    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>