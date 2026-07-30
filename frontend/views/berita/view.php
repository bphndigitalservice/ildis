<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\components\LazyImage;
use common\components\HtmlSanitizer;

/* @var $this yii\web\View */
/* @var $model frontend\models\Berita */
/* @var $model2 frontend\models\search\BeritaSearch */

$this->title = $model->judul;

// --- SEO Metatags & Open Graph ---
$baseUrl = Url::to(['/'], true);
$currentUrl = Url::current([], true);
$desc = !empty($model->isi) ? strip_tags($model->isi) : $model->judul;
$desc = mb_strimwidth($desc, 0, 160, "...");

$this->registerMetaTag(['name' => 'description', 'content' => $desc]);

// Open Graph
$this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
$this->registerMetaTag(['property' => 'og:description', 'content' => $desc]);
$this->registerMetaTag(['property' => 'og:type', 'content' => 'article']);
$this->registerMetaTag(['property' => 'og:url', 'content' => $currentUrl]);
if (!empty($model->image)) {
    $imageUrl = Url::to('@web/common/dokumen/' . $model->image, true);
    $this->registerMetaTag(['property' => 'og:image', 'content' => $imageUrl]);
    $this->registerLinkTag(['rel' => 'preload', 'as' => 'image', 'href' => $imageUrl]);
}

// Twitter
$this->registerMetaTag(['name' => 'twitter:card', 'content' => 'summary_large_image']);
$this->registerMetaTag(['name' => 'twitter:title', 'content' => $this->title]);
$this->registerMetaTag(['name' => 'twitter:description', 'content' => $desc]);

$this->params['breadcrumbs'][] = ['label' => 'Berita', 'url' => ['index']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>

<div class="berita-view-wrapper">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <article class="berita-article">
                    <?php if ($model->image): ?>
                        <div class="berita-article__hero">
                            <?= LazyImage::img('@web/common/dokumen/' . $model->image, [
                                'class' => 'berita-article__hero-image',
                                'alt' => $model->judul,
                            ], false) ?>
                        </div>
                    <?php endif; ?>

                    <div class="berita-article__body">
                        <time class="berita-article__date" datetime="<?= Html::encode($model->tanggal) ?>">
                            <?= \common\components\DateHelper::formatIndonesian($model->tanggal) ?>
                        </time>

                        <h1 class="berita-article__title">
                            <?= Html::encode($model->judul) ?>
                        </h1>

                        <div class="berita-article__content">
                            <?= HtmlSanitizer::purify($model->isi) ?>
                        </div>

                        <footer class="berita-article__footer">
                            <?= Html::a('← Kembali ke daftar berita', ['index'], ['class' => 'berita-article__back']) ?>
                        </footer>
                    </div>
                </article>
            </div>

            <div class="col-lg-4">
                <?= $this->render('_sidebar', ['searchModel' => $model2]) ?>
            </div>
        </div>
    </div>
</div>

<?= $this->render('_berita-shared-styles') ?>

<style>
.berita-view-wrapper {
    background-color: #f8fafc;
    min-height: 100vh;
    padding: 100px 0 40px;
}

.berita-article {
    background: #ffffff;
    border: 1px solid #e8edf4;
    border-radius: 0.75rem;
    overflow: hidden;
}

.berita-article__hero {
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background-color: #e8edf4;
}

.berita-article__hero-image {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.berita-article__body {
    padding: 1.75rem 1.75rem 1.5rem;
}

@media (min-width: 768px) {
    .berita-article__body {
        padding: 2rem 2.25rem 1.75rem;
    }
}

.berita-article__date {
    display: block;
    font-size: 0.8125rem;
    color: #64748b;
    margin-bottom: 0.75rem;
}

.berita-article__title {
    font-size: clamp(1.375rem, 3vw, 1.75rem);
    font-weight: 700;
    color: #1a2752;
    letter-spacing: -0.01em;
    line-height: 1.3;
    margin: 0 0 1.5rem;
}

.berita-article__content {
    font-size: 1rem;
    line-height: 1.75;
    color: #334155;
    text-align: justify;
}

.berita-article__content p {
    margin-bottom: 1.25rem;
}

.berita-article__content p:last-child {
    margin-bottom: 0;
}

.berita-article__footer {
    margin-top: 2rem;
    padding-top: 1.25rem;
    border-top: 1px solid #e8edf4;
}

.berita-article__back {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1a2752;
    text-decoration: none;
}

.berita-article__back:hover {
    color: #274685;
    text-decoration: underline;
    text-underline-offset: 2px;
}
</style>
