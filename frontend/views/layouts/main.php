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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <?= $this->render('_google_analytics') ?>

</head>


<body>

    <?php $this->beginBody() ?>

    <a class="visually-hidden-focusable skip-link" href="#main-content">Lewati ke konten utama</a>

    <!-- start main-wrapper section -->

    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex justify-content-between align-items-center">

            <div class="logo">
              <?= Html::a(\common\components\LazyImage::img('@web/common/dokumen/' . $logo->isi_konfig, [
                  'id' => 'logo',
                  'alt' => Html::encode($siteName),
              ], false), ['/'], ['class' => 'navbar-brand width-200px sm-width-180px xs-width-150px']); ?>
            </div>

          <nav id="navbar" class="navbar" aria-label="Navigasi utama">
            <div class="navbar-menu-desktop">
              <?= $this->render('menu.php') ?>
            </div>
            <button type="button" class="mobile-nav-toggle bi bi-list border-0 bg-transparent" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-nav"></button>
          </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <div id="mobile-nav" class="mobile-nav" aria-hidden="true">
      <div class="mobile-nav-backdrop" aria-hidden="true"></div>
      <aside class="mobile-nav-drawer" role="dialog" aria-modal="false" aria-label="Menu navigasi">
        <div class="mobile-nav-header">
          <div class="mobile-nav-header__brand">
            <a href="<?= Url::to(['/']) ?>" class="mobile-nav-header__logo-link">
              <?= \common\components\LazyImage::img('@web/common/dokumen/' . $logo->isi_konfig, [
                  'class' => 'mobile-nav-header__logo',
                  'alt' => Html::encode($siteName),
              ], false) ?>
            </a>
            <span class="mobile-nav-header__title">Menu</span>
          </div>
          <button type="button" class="mobile-nav-close" aria-label="Tutup menu">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
          </button>
        </div>
        <form class="mobile-nav-search" action="<?= Url::to(['dokumen/index']) ?>" method="get" role="search">
          <i class="bi bi-search mobile-nav-search__icon" aria-hidden="true"></i>
          <input
            type="search"
            name="DokumenSearch[judul]"
            class="mobile-nav-search__input"
            placeholder="Cari dokumen..."
            autocomplete="off"
            aria-label="Cari dokumen"
          >
        </form>
        <div class="mobile-nav-body">
          <?= $this->render('menu.php') ?>
        </div>
      </aside>
    </div>

    <main id="main-content" role="main">
    <?= Alert::widget() ?>
    <?= $content ?>
    </main>

        <?= $this->render('footer.php') ?>

    <div id="a11y-widget" class="a11y-widget" aria-label="Widget aksesibilitas">
        <button
            type="button"
            id="a11y-widget-toggle"
            class="a11y-widget__toggle"
            aria-expanded="false"
            aria-controls="a11y-widget-panel"
            aria-label="Buka menu aksesibilitas"
            title="Menu aksesibilitas"
        >
            <i class="bi bi-universal-access-circle" aria-hidden="true"></i>
        </button>
        <div id="a11y-widget-panel" class="a11y-widget__panel" hidden role="region" aria-label="Menu aksesibilitas">
            <div class="a11y-widget__header">
                <h2 class="a11y-widget__title">Aksesibilitas</h2>
                <button type="button" id="a11y-widget-close" class="a11y-widget__close" aria-label="Tutup menu aksesibilitas">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>
            </div>
            <ul class="a11y-widget__menu">
                <li class="a11y-widget__item">
                    <button type="button" class="a11y-widget__action" data-a11y-action="font-increase">
                        <i class="bi bi-zoom-in" aria-hidden="true"></i>
                        <span>Perbesar teks</span>
                    </button>
                </li>
                <li class="a11y-widget__item">
                    <button type="button" class="a11y-widget__action" data-a11y-action="font-decrease">
                        <i class="bi bi-zoom-out" aria-hidden="true"></i>
                        <span>Perkecil teks</span>
                    </button>
                </li>
                <li class="a11y-widget__divider" aria-hidden="true"></li>
                <li class="a11y-widget__item">
                    <button type="button" class="a11y-widget__action" data-a11y-action="a11y-high-contrast">
                        <i class="bi bi-circle-half" aria-hidden="true"></i>
                        <span>Kontras tinggi</span>
                    </button>
                </li>
                <li class="a11y-widget__item">
                    <button type="button" class="a11y-widget__action" data-a11y-action="a11y-grayscale">
                        <i class="bi bi-palette" aria-hidden="true"></i>
                        <span>Mode abu-abu</span>
                    </button>
                </li>
                <li class="a11y-widget__item">
                    <button type="button" class="a11y-widget__action" data-a11y-action="a11y-highlight-links">
                        <i class="bi bi-link-45deg" aria-hidden="true"></i>
                        <span>Sorot tautan</span>
                    </button>
                </li>
                <li class="a11y-widget__item">
                    <button type="button" class="a11y-widget__action" data-a11y-action="a11y-readable-font">
                        <i class="bi bi-type" aria-hidden="true"></i>
                        <span>Font mudah dibaca</span>
                    </button>
                </li>
                <li class="a11y-widget__divider" aria-hidden="true"></li>
                <li class="a11y-widget__item">
                    <button type="button" class="a11y-widget__action" data-a11y-action="read-aloud">
                        <i class="bi bi-volume-up" aria-hidden="true"></i>
                        <span>Baca layar</span>
                    </button>
                </li>
                <li class="a11y-widget__item">
                    <button type="button" class="a11y-widget__action" data-a11y-action="stop-read">
                        <i class="bi bi-stop-circle" aria-hidden="true"></i>
                        <span>Hentikan bacaan</span>
                    </button>
                </li>
                <li class="a11y-widget__divider" aria-hidden="true"></li>
                <li class="a11y-widget__item">
                    <button type="button" class="a11y-widget__action" data-a11y-action="reset">
                        <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                        <span>Atur ulang</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

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
