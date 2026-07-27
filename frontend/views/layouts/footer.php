<?php

use yii\helpers\Html;
use backend\models\FrontendConfig;
use common\models\FooterSection;
use common\models\VisitorStats;

// Fetch visitor stats
$today = date('Y-m-d');
$thisWeek = date('Y-m-d', strtotime('monday this week'));
$thisMonth = date('Y-m-01');
$thisYear = date('Y-01-01');

$todayStat = VisitorStats::find()->where(['stat_type' => VisitorStats::TYPE_DAILY, 'stat_date' => $today, 'document_id' => null])->one();
$weekStat = VisitorStats::find()->where(['stat_type' => VisitorStats::TYPE_WEEKLY, 'stat_date' => $thisWeek, 'document_id' => null])->one();
$monthStat = VisitorStats::find()->where(['stat_type' => VisitorStats::TYPE_MONTHLY, 'stat_date' => $thisMonth, 'document_id' => null])->one();
$yearStat = VisitorStats::find()->where(['stat_type' => VisitorStats::TYPE_YEARLY, 'stat_date' => $thisYear, 'document_id' => null])->one();

$todayVisits = $todayStat ? (int)$todayStat->unique_visits : 0;
$weekVisits = $weekStat ? (int)$weekStat->unique_visits : 0;
$monthVisits = $monthStat ? (int)$monthStat->unique_visits : 0;
$yearVisits = $yearStat ? (int)$yearStat->unique_visits : 0;

$logo = FrontendConfig::findOne(3);
$instansi = FrontendConfig::findOne(2);
$deskripsi = FrontendConfig::findOne(4);
$alamat = FrontendConfig::findOne(5);
$nomor = FrontendConfig::findOne(6);
$email = FrontendConfig::findOne(7);

$cleanInstansi = $instansi ? trim(strip_tags($instansi->isi_konfig)) : 'Badan Pembinaan Hukum Nasional - Kementerian Hukum R.I';
$cleanAlamat = $alamat ? trim(strip_tags($alamat->isi_konfig)) : 'Jl. Mayjend Sutoyo, Cililitan, Jakarta Timur';
$cleanNomor = $nomor ? trim(preg_replace('/\s+/', ' ', strip_tags($nomor->isi_konfig))) : 'Telp +62-21 8091909 (hunting) Faks +62-21 8011753';
$cleanEmail = $email ? trim(preg_replace('/\s+/', ' ', strip_tags($email->isi_konfig))) : 'humas@bphn.go.id · bphn.humaskerjasamantu@gmail.com';

if ($cleanNomor !== '' && preg_match('/^\(?0\d{1,3}\)?\s*[-–—]?\s*$/u', $cleanNomor)) {
    $cleanNomor = '';
}

$sections = FooterSection::getActiveSections();
$navSections = [];
$socialSections = [];
foreach ($sections as $section) {
    if ($section->type === FooterSection::TYPE_NAV) {
        $navSections[] = $section;
    } elseif ($section->type === FooterSection::TYPE_SOCIAL) {
        $socialSections[] = $section;
    }
}

$hasDynamicContent = !empty($navSections) || !empty($socialSections);
$socialSection = !empty($socialSections) ? $socialSections[0] : null;
$socialLinks = $socialSection ? $socialSection->activeLinks : [];

$versionPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'VERSION';
$appVersion = file_exists($versionPath) ? trim(file_get_contents($versionPath)) : 'dev';

?>

<!-- ======= Footer ======= -->
<footer class="footer bphn-footer" role="contentinfo">
  <div class="container footer-shell">
    <div class="row footer-main">
      <div class="col-lg-5 col-md-12 footer-brand mb-4 mb-lg-0 pe-lg-5">
        <h2 class="footer-brand__title">
          Jaringan Dokumentasi dan Informasi <span class="footer-brand__accent">Hukum Nasional</span>
        </h2>
        <div class="footer-contact">
          <p class="footer-contact__org"><?= Html::encode($cleanInstansi) ?></p>
          <p class="footer-contact__item">
            <i class="bi bi-geo-alt footer-contact__icon" aria-hidden="true"></i>
            <span><?= Html::encode($cleanAlamat) ?></span>
          </p>
          <?php if ($cleanNomor !== ''): ?>
          <p class="footer-contact__item">
            <i class="bi bi-telephone footer-contact__icon" aria-hidden="true"></i>
            <span><?= Html::encode(str_replace('Faks', ' · Faks', $cleanNomor)) ?></span>
          </p>
          <?php endif; ?>
          <p class="footer-contact__item footer-contact__item--last">
            <i class="bi bi-envelope footer-contact__icon" aria-hidden="true"></i>
            <span><?= Html::encode($cleanEmail) ?></span>
          </p>
        </div>
      </div>

      <div class="col-lg-7 col-md-12">
        <div class="row footer-nav-columns g-4 g-lg-0">
<?php if ($hasDynamicContent): ?>
    <?php foreach ($navSections as $section): ?>
      <?php if (!empty($section->activeLinks)): ?>
          <div class="col-6 col-md-6 col-lg-4 footer-nav-col ps-lg-5">
            <h3 class="footer-nav__title"><?= Html::encode($section->title) ?></h3>
            <ul class="footer-nav__list list-unstyled mb-0">
              <?php foreach ($section->activeLinks as $link): ?>
                <li><?= Html::a(Html::encode($link->label), Html::encode($link->url), array_filter([
                    'class' => 'footer-link',
                    'target' => $link->open_in_new_tab ? '_blank' : null,
                    'rel' => $link->open_in_new_tab ? 'noopener noreferrer' : null,
                ])) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
      <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
          <div class="col-6 col-md-6 col-lg-5 footer-nav-col ps-lg-5">
            <h3 class="footer-nav__title">Layanan</h3>
            <ul class="footer-nav__list list-unstyled mb-0">
              <li><a href="#" class="footer-link">Pengaduan</a></li>
              <li><a href="#" class="footer-link">Penilaian</a></li>
            </ul>
          </div>

          <div class="col-6 col-md-6 col-lg-7 footer-nav-col">
            <h3 class="footer-nav__title">Tentang</h3>
            <ul class="footer-nav__list list-unstyled mb-0">
              <li><a href="/" class="footer-link">Beranda</a></li>
              <li><a href="#" class="footer-link">FAQ</a></li>
              <li><a href="#" class="footer-link">Kontak Kami</a></li>
            </ul>
          </div>
<?php endif; ?>
        </div>
      </div>
    </div>

    <div class="footer-analytics" aria-label="Statistik pengunjung">
      <div class="analytics-strip">
        <span class="analytics-title"><i class="bi bi-people" aria-hidden="true"></i> Pengunjung</span>
        <span class="analytics-stat">
          <span class="analytics-num"><?= $todayVisits ?></span>
          <span class="analytics-period">hari ini</span>
        </span>
        <span class="analytics-dot" aria-hidden="true"></span>
        <span class="analytics-stat">
          <span class="analytics-num"><?= $weekVisits ?></span>
          <span class="analytics-period">minggu ini</span>
        </span>
        <span class="analytics-dot" aria-hidden="true"></span>
        <span class="analytics-stat">
          <span class="analytics-num"><?= $monthVisits ?></span>
          <span class="analytics-period">bulan ini</span>
        </span>
        <span class="analytics-dot" aria-hidden="true"></span>
        <span class="analytics-stat">
          <span class="analytics-num"><?= $yearVisits ?></span>
          <span class="analytics-period">tahun ini</span>
        </span>
        <span class="analytics-title mt-2">
          <?= Html::a('Statistik dokumen hukum', ['/statistik'], ['class' => 'footer-link']) ?>
        </span>
      </div>
    </div>

    <div class="footer-bottom">
      <p class="footer-bottom__copy">
        &copy; <?= date('Y') ?> <?= Html::encode($cleanInstansi) ?>
        powered by <a href="https://ildis.bphn.go.id" target="_blank" rel="noopener noreferrer" class="footer-bottom__ildis">ILDIS</a> v<?= Html::encode($appVersion) ?>
      </p>

      <div class="footer-bottom__meta">
        <div class="footer-bottom__locale">
          <i class="bi bi-globe" aria-hidden="true"></i>
          <span>Indonesia</span>
        </div>

        <div class="footer-bottom__social">
<?php if (!empty($socialLinks)): ?>
    <?php foreach ($socialLinks as $link): ?>
          <?php
          $linkOptions = ['class' => 'footer-social'];
          if ($link->open_in_new_tab) {
              $linkOptions['target'] = '_blank';
              $linkOptions['rel'] = 'noopener noreferrer';
          }
          ?>
          <?php if ($link->icon_class === 'bi bi-twitter-x'): ?>
          <a href="<?= Html::encode($link->url) ?>" <?= Html::renderTagAttributes($linkOptions) ?>>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
              <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
            </svg>
          </a>
          <?php elseif ($link->icon_class): ?>
          <a href="<?= Html::encode($link->url) ?>" <?= Html::renderTagAttributes($linkOptions) ?>><i class="<?= Html::encode($link->icon_class) ?>"></i></a>
          <?php else: ?>
          <a href="<?= Html::encode($link->url) ?>" <?= Html::renderTagAttributes($linkOptions) ?>><i class="bi bi-link-45deg"></i></a>
          <?php endif; ?>
    <?php endforeach; ?>
<?php elseif ($hasDynamicContent): ?>
<?php else: ?>
          <a href="#" class="footer-social"><i class="bi bi-facebook"></i></a>
          <a href="#" class="footer-social"><i class="bi bi-instagram"></i></a>
          <a href="#" class="footer-social">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
              <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
            </svg>
          </a>
          <a href="#" class="footer-social"><i class="bi bi-youtube"></i></a>
<?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <style>
    .bphn-footer {
      background-color: #1a2752;
      color: #b8c5db;
      font-size: 0.875rem;
      padding: 3rem 0 1.75rem;
    }

    .footer-shell {
      padding-left: 1.25rem;
      padding-right: 1.25rem;
    }

    .footer-main {
      padding-bottom: 1.75rem;
    }

    .footer-brand__title {
      margin: 0 0 1.25rem;
      font-size: 1.05rem;
      font-weight: 700;
      line-height: 1.35;
      letter-spacing: 0.01em;
      color: #f4f6fa;
    }

    .footer-brand__accent {
      color: #ffc107;
    }

    .footer-contact__org {
      margin: 0 0 1rem;
      font-weight: 500;
      color: #dbe4f2;
      line-height: 1.55;
    }

    .footer-contact__item {
      display: flex;
      align-items: flex-start;
      gap: 0.65rem;
      margin: 0 0 0.75rem;
      line-height: 1.55;
      color: #b8c5db;
    }

    .footer-contact__item--last {
      margin-bottom: 0;
    }

    .footer-contact__icon {
      flex-shrink: 0;
      margin-top: 0.15rem;
      font-size: 0.9rem;
      color: #8fa3c4;
    }

    .footer-nav__title {
      margin: 0 0 0.85rem;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #f4f6fa;
    }

    .footer-nav__list {
      line-height: 1.9;
    }

    .footer-nav__list li + li {
      margin-top: 0.15rem;
    }

    .bphn-footer a.footer-link,
    .bphn-footer a.footer-link-muted,
    .bphn-footer a.footer-social {
      color: #c8d4e8;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .bphn-footer a.footer-link:hover,
    .bphn-footer a.footer-link:focus-visible,
    .bphn-footer a.footer-link-muted:hover,
    .bphn-footer a.footer-link-muted:focus-visible,
    .bphn-footer a.footer-social:hover,
    .bphn-footer a.footer-social:focus-visible {
      color: #f4f6fa;
    }

    .bphn-footer a.footer-social i,
    .bphn-footer a.footer-social svg {
      color: currentColor;
      fill: currentColor;
    }

    .bphn-footer a.footer-social svg {
      vertical-align: middle;
      transform: translateY(-1px);
    }

    .footer-analytics {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding: 1.1rem 0 1.25rem;
      margin-bottom: 1.25rem;
    }

    .analytics-strip {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      gap: 0.35rem 0;
    }

    .analytics-title {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      margin-right: 0.85rem;
      font-size: 0.6875rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: #8fa3c4;
      white-space: nowrap;
    }

    .analytics-title i {
      font-size: 0.8rem;
      color: #ffc107;
    }

    .analytics-stat {
      display: inline-flex;
      align-items: baseline;
      gap: 0.25rem;
      white-space: nowrap;
    }

    .analytics-num {
      font-size: 0.9rem;
      font-weight: 700;
      color: #f4f6fa;
      font-variant-numeric: tabular-nums;
    }

    .analytics-period {
      font-size: 0.6875rem;
      color: #8fa3c4;
    }

    .analytics-dot {
      display: inline-block;
      width: 3px;
      height: 3px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.18);
      margin: 0 0.65rem;
    }

    .footer-bottom {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
      text-align: center;
    }

    .footer-bottom__copy {
      margin: 0;
      max-width: 36rem;
      font-size: 0.75rem;
      line-height: 1.55;
      color: #dbe4f2;
    }

    .bphn-footer .footer-bottom__copy a.footer-bottom__ildis,
    .bphn-footer .footer-bottom__copy a.footer-bottom__ildis:visited {
      color: #ffc107;
      text-decoration: none;
      font-weight: 600;
    }

    .bphn-footer .footer-bottom__copy a.footer-bottom__ildis:hover,
    .bphn-footer .footer-bottom__copy a.footer-bottom__ildis:focus-visible {
      color: #ffd54f;
      text-decoration: underline;
      text-underline-offset: 2px;
    }

    .footer-bottom__meta {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      gap: 1rem 1.25rem;
    }

    .footer-bottom__locale {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.8125rem;
      color: #dbe4f2;
    }

    .footer-bottom__social {
      display: inline-flex;
      align-items: center;
      gap: 1rem;
      padding-left: 0;
      font-size: 1.05rem;
      border-left: none;
    }

    @media (min-width: 992px) {
      .footer-shell {
        padding-left: 15px;
        padding-right: 15px;
      }

      .footer-bottom {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        text-align: left;
      }

      .footer-bottom__meta {
        justify-content: flex-end;
      }

      .footer-bottom__social {
        padding-left: 1.25rem;
        border-left: 1px solid rgba(255, 255, 255, 0.12);
      }
    }

    @media (max-width: 767.98px) {
      .bphn-footer {
        padding: 2.25rem 0 1.5rem;
      }

      .footer-shell {
        padding-left: 1.125rem;
        padding-right: 1.125rem;
      }

      .footer-brand__title {
        font-size: 0.95rem;
        margin-bottom: 1rem;
      }

      .footer-contact__org {
        font-size: 0.8125rem;
      }

      .footer-contact__item {
        font-size: 0.8125rem;
        margin-bottom: 0.65rem;
      }

      .footer-nav-columns {
        margin-top: 0.25rem;
      }

      .footer-nav__title {
        margin-bottom: 0.65rem;
      }

      .footer-nav__list {
        line-height: 1.75;
        font-size: 0.8125rem;
      }

      .analytics-strip {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem 1rem;
        justify-items: stretch;
      }

      .analytics-title {
        grid-column: 1 / -1;
        justify-content: center;
        margin: 0 0 0.15rem;
      }

      .analytics-stat {
        justify-content: center;
        padding: 0.55rem 0.5rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.06);
      }

      .analytics-dot {
        display: none;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      .footer-link,
      .footer-link-muted,
      .footer-social {
        transition: none;
      }
    }
  </style>
</footer>