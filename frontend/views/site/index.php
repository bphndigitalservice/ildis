<?php


use backend\models\DokumenJdih;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Dokumen;
use backend\models\FrontendConfig;
use miloschuman\highcharts\Highcharts;
use dosamigos\chartjs\ChartJs;
$ikm = FrontendConfig::findOne(16);
$video = FrontendConfig::findOne(17);
$ios = FrontendConfig::findOne(18);
$android = FrontendConfig::findOne(19);

/* masukkan teks dibawah ini disetiap view dari halaman */

$this->title = 'Jaringan Dokumentasi dan Informasi Hukum';
$this->description = 'Jaringan Dokumentasi dan Informasi Hukum';
$this->keywords = ['Jaringan', 'Dokumentasi', 'Informasi', 'Hukum'];
$this->generator = 'Badan Pembinaan Hukum Nasional';
/* masukkan teks diatas dibawah ini disetiap view dari halaman */


?>

<!-- ======= Hero Section ======= -->
<section class="hero-section" id="hero">

  <div class="wave">
    <svg width="100%" height="355px" viewBox="0 0 1920 355" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Apple-TV" transform="translate(0.000000, -402.000000)" fill="#FFFFFF">
          <path d="M0,439.134243 C175.04074,464.89273 327.944386,477.771974 458.710937,477.771974 C654.860765,477.771974 870.645295,442.632362 1205.9828,410.192501 C1429.54114,388.565926 1667.54687,411.092417 1920,477.771974 L1920,757 L1017.15166,757 L0,757 L0,439.134243 Z" id="Path"></path>
        </g>
      </g>
    </svg>

  </div>

  <div class="container">
    <div class="row align-items-center">
      <div class="col-12 hero-text-image">
        <div class="row">
          <div class="col-lg-8 text-center text-lg-start">
            <h1 class="cd-headline slide">
              <?php $instansi = FrontendConfig::findOne(2);
              echo $instansi->isi_konfig;
              ?>
              <br>
            </h1>
            <p class="mb-5" data-aos="fade-right" data-aos-delay="100">Jaringan Dokumentasi dan Informasi Hukum Nasional yang selanjutnya disebut JDIHN adalah wadah pendayagunaan bersama atas dokumen hukum secara tertib terpadu, dan berkesinambungan, serta merupakan sarana pemberian pelayanan informasi hukum secara lengkap, akurat, mudah, dan cepat.</p>
            <p data-aos="fade-right" data-aos-delay="200" data-aos-offset="-500"><a href="<?= $video->isi_konfig ?>" class="btn btn-outline-white">Watch Video</a></p>
          </div>
          <div class="col-lg-4 iphone-wrap">
            <img src="frontend/assets/img/phone_1.png" alt="Image" class="phone-1" data-aos="fade-right">
            <img src="frontend/assets/img/phone_1.png" alt="Image" class="phone-2" data-aos="fade-right" data-aos-delay="200">
          </div>
        </div>
      </div>
    </div>
  </div>
</section><!-- End Hero -->

<main id="main">

<section class="section">
      <div class="container">
        <?php

        $peraturan = DokumenJdih::find()
        ->where(['tipe_dokumen' => 1])
        ->count();


        $monografi = DokumenJdih::find()
        ->where(['tipe_dokumen' => 2])
        ->count();
        $artikel = DokumenJdih::find()
        ->where(['tipe_dokumen' => 3])
        ->count();

        $putusan = DokumenJdih::find()
        ->where(['tipe_dokumen' => 4])
        ->count();


        echo Highcharts::widget([
         'options' => [
          'title' => ['text' => 'Jenis'],
          'xAxis' => [
            'categories' => ['Peraturan', 'Monografi', 'Artikel','Putusan']
          ],
          'yAxis' => [
            'title' => ['text' => 'Jumlah']
          ],
          'series' => [
            ['type' => 'column', 'name' => 'Peraturan', 'data' => array((int)$peraturan)],
            ['type' => 'column', 'name' => 'Monografi', 'data' => array((int)$monografi)],
            ['type' => 'column', 'name' => 'Artikel', 'data' => array((int)$artikel)],
            ['type' => 'column', 'name' => 'Putusan', 'data' => array((int)$putusan)],
          ]
        ]
      ]);
      ?>
      </div>
      <br>
      <div class="container">
                    <form id="w0" action="dokumen/index" method="get" data-pjax="1">
                        <div class="form-row align-items-center">
                            <div class="col-md-10 my-1">
                                <input type="text" class="form-control" id="dokumensearch-judul" name="DokumenSearch[judul]" placeholder="cari dokumen hukum...">
                            </div>
                            <br>

                            <div class="col-md-2 my-1">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                            <br>
                        </div>
                    </form>
       <br>
      </div>
      <div class="container">
       <p class="float-left mb-3 section-pencarianlanjut"><?= Html::a('&nbsp&nbsp&nbspPencarian Lebih Lanjut >>', ['dokumen/index'], ['class' => 'text-white']); ?>
      </p>
      </div>
</section>

<section id="services" class="services">
      <div class="container">
        <div class="row justify-content-center text-center mb-5">
          <div class="col-md-5">
              <h2 class="section-heading">Koleksi Kami</h2><br>
          </div>
       </div>

        <div class="row">
          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="icon"><i class="bi bi-file-earmark"></i></div>
              <a href="dokumen/peraturan">
                <h4 class="title">Peraturan</h3>
                  <p class="description">Jenis dokumen hukum: peraturan perundang-undangan</p><br><br> 
              </a>
              <!-- <h4><a href="dokumen/peraturan">Peraturan</a></h4> -->
              <p>
                <h3>&nbsp&nbsp<?= Dokumen::find()->total(1); ?></h3>
              </p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="icon"><i class="bi bi-book"></i></div>
              <a href="dokumen/monografi">
                <h4 class="title">Monografi Hukum</h3>
                  <p class="description">Jenis dokumen hukum: buku hukum, naskah akademik, analisis dan evaluasi, rancangan peraturan perundang-undangan
                  </p> 
              </a>
              <!-- <h4><a href="dokumen/peraturan">Peraturan</a></h4> -->
              <p>
                <h3>&nbsp&nbsp<?= Dokumen::find()->total(2); ?></h3>
              </p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="icon"><i class="bi bi-check-square"></i></div>
              <a href="dokumen/artikel">
                <h4 class="title">Artikel Hukum</h3>
                  <p class="description">Jenis dokumen hukum: artikel hukum, jurnal hukum, majalah hukum, konvensi</p><br> 
              </a>
              <!-- <h4><a href="dokumen/peraturan">Peraturan</a></h4> -->
              <p>
                <h3>&nbsp&nbsp<?= Dokumen::find()->total(3); ?></h3>
              </p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="icon"><i class="bi bi-journal"></i></div>
              <a href="dokumen/putusan">
                <h4 class="title">Putusan</h3>
                  <p class="description">Jenis dokumen hukum: putusan ma, putusan mk</p>
                  <br><br> 
              </a>
              <!-- <h4><a href="dokumen/peraturan">Peraturan</a></h4> -->
              <p>
                <h3>&nbsp&nbsp<?= Dokumen::find()->total(4); ?></h3>
              </p>
            </div>
          </div>
        </div>

        <br>
        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="icon"><i class="bi bi-check-square"></i></div>
              <a href="dokumen/berlaku">
                <h4 class="title">Peraturan Berlaku</h3>
                  <p class="description">Kumpulan peraturan perundang-undangan yang masih berlaku</p> 
              </a>
              <!-- <h4><a href="dokumen/peraturan">Peraturan</a></h4> -->
             <p>
                <h3>&nbsp&nbsp<?= dokumen::find()
                ->where(['status' => 'Berlaku'])
                ->count(); ?></h3>
              </p>
              <p>--</p>
            </div>
          </div>
           <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="icon"><i class="bi bi-x-circle"></i></div>
              <a href="dokumen/tberlaku">
                <h4 class="title" style="color: red;">Peraturan Tidak Berlaku</h3>
                  <p class="description">Kumpulan peraturan perundang-undangan yang sudah tidak berlaku</p> 
              </a>
              <!-- <h4><a href="dokumen/peraturan">Peraturan</a></h4> -->
             <p>
                <h3>&nbsp&nbsp<?= dokumen::find()
                ->where(['status' => 'Tidak Berlaku'])
                ->count(); ?></h3>
              </p>
              <p>--</p>
            </div>
          </div>
          <div class="col-md-3"></div>
        </div>
        
        </div>
      </div>
</section><!-- End Services Section -->

<section class="section">
  <div class="container">
    <div class="row justify-content-center text-center mb-5" data-aos="fade">
      <div class="col-md-2 mb-5">
           <b>JUMLAH PENGUNJUNG</b> 
                            <a href='https://acadooghostwriter.com/'style='visibility: hidden'>ACADOOghostwriter.com</a> <script type='text/javascript' src='https://www.freevisitorcounters.com/auth.php?id=38792cec451d4d587a7f344e1225aa18912c881c'></script>
                            <script type="text/javascript" src="https://www.freevisitorcounters.com/en/home/counter/683283/t/6"></script>
      </div>
      <div class="col-md-8 mb-5">
        <img src="frontend/assets/img/undraw_svg_1.png" alt="Image" class="img-fluid">
      </div>
      <div class="col-md-2 mb-5">
        
      </div>
    </div>

  <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-4">
        <div class="step">
          <!-- <span class="number">01</span> -->
          <h3><a href="dokumen">Koleksi Dokumen</a></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="step">
          <!-- <span class="number">02</span> -->
          <h3><a href="https://jdihn.go.id/">Portal JDIHN</a></h3>
        </div>
      </div>
      <div class="col-md-2">
      </div>
    </div>
  </div>
</section>

<section class="section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 me-auto">
            <h2 class="mb-4">Koleksi Unggulan</h2><br>
              <ul>
                 <div class="row align-items-center">
                    <div class="col-md-4 me-auto ">
                <a href="dokumen/uu"><h5 class="mb-3" style="color: dodgerblue;">Undang-undang</h5></a>
                <a href="dokumen/buku"><h5 class="mb-3" style="color: dodgerblue;">Buku Hukum</h5></a>
                <a href="dokumen/majalah"><h5 class="mb-3" style="color: dodgerblue;">Majalah Hukum</h5></a>
                <a href="dokumen/putusan"><h5 class="mb-3" style="color: dodgerblue;">Putusan MA</h5></a>
                </div>
              </ul>
            <!-- <p><a href="" class="btn btn-primary">Get Started</a></p> -->
          </div>
          <div class="col-md-6" data-aos="fade-left">
            <img src="frontend/assets/img/undraw_svg_2.png" alt="Image" class="img-fluid">
          </div>
        </div>
      </div>
</section>

<section class="section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-4 ms-auto order-2">
        <h2 class="mb-4">Indeks Kepuasan Masyarakat</h2>
        <h5 class="mb-3">Mohon mengisi survey Indeks Kepuasan Masyarakat (IKM) sebagai masukan untuk penyajian website yang lebih baik.</h5><br>
        <p>
	<h5 class="mb-3"><a href="https://survei-bsk.kemenkumham.go.id/ly/xcdj73TH">SILAKAN PARTISIPASI SURVEY KAMI</a></h5></p>
      </div>
      <div class="col-md-6" data-aos="fade-right">
        <img src="frontend/assets/img/undraw_svg_3.svg" alt="Image" class="img-fluid">
      </div>
    </div>
  </div>
</section>

<!-- ======= Testimonials Section ======= -->

<section class="section border-top border-bottom">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-md-4">
        <h2 class="section-heading">Berita Terbaru</h2><br><br>
      </div>
      <div class="row">
        <?php foreach ($berita as $data) : ?>
          <!-- start blog -->
          <div class="col-lg-4 sm-margin-30px-bottom">
            <div class="card border-0 shadow h-100">
              <?= Html::a(Html::img('@web/common/dokumen/' . $data->image, ['class' => 'card-img-top rounded']), ['berita/view', 'id' => $data->id]); ?>

              <div class="card-body padding-30px-all">
                <h5 class="card-title font-size22 font-weight-500 magin-20px-bottom">
                  <a href="blog-details.html" class="text-extra-dark-gray"><?= Html::a(implode(" ", array_slice(explode(" ", $data->judul), 0, 3)) . ' .....', ['berita/view', 'id' => $data->id]) ?></a>
                </h5>
                <p class="no-margin-bottom"><?= implode(" ", array_slice(explode(" ", strip_tags($data->isi)), 0, 20)) . ' .....' ?></p>
              </div>
            </div>
          </div>
          <?php endforeach  ?>'

          <!-- end blog -->
        </div>
      </div>
      <div class="float-right mb-3 section-selengkapnya">
        <?= Html::a('Selengkapnya...', ['berita/index']); ?>
      </div>
    </div>
</section><!-- End Testimonials Section -->

<!-- start berita section -->

<?php if (!empty($berita)) : ?>

<?php else : ?>
<?php endif; ?>
<!-- end berita section -->

<?php
$this->registerJS('$(document).ready(function(){$("#myModal").modal("show");});');
?>

