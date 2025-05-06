<?php

use yii\helpers\Html;
use backend\models\FrontendConfig;

$logo = FrontendConfig::findOne(3);
$fb = FrontendConfig::findOne(13);
$yt = FrontendConfig::findOne(14);
$ig = FrontendConfig::findOne(15);
$instansi = FrontendConfig::findOne(2);
$deskripsi = FrontendConfig::findOne(4);
$alamat = FrontendConfig::findOne(5);
$nomor = FrontendConfig::findOne(6);
$email = FrontendConfig::findOne(7);

?>

<!-- ======= Footer ======= -->
  <footer class="footer" role="contentinfo">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
          <h3>Media Sosial</h3>
          <p>Silakan kunjungi media sosial kami untuk mengetahui kegiatan JDIH terkini</p></p>
          <p class="social">
            <a href="<?= $yt->isi_konfig ?>"><span class="bi bi-youtube"></span></a>
            <a href="<?= $fb->isi_konfig ?>"><span class="bi bi-facebook"></span></a>
            <a href="<?= $ig->isi_konfig ?>"><span class="bi bi-instagram"></span></a>
          </p>
        </div>
        <div class="col-md-7 ms-auto">
          <div class="row site-section pt-0">
            <div class="col-md-3 mb-3 mb-md-0"> 
            </div>
            <div class="col-md-5 mb-5 mb-md-0">
              <h3>Hubungi Kami</h3>
              <ul class="list-unstyled">
                <li></p>
                    <?= $instansi->isi_konfig ?> </p>
                    <?= $deskripsi->isi_konfig ?></b></p>
                    <?= $alamat->isi_konfig ?></p>
                    <?= $nomor->isi_konfig ?></p>
                    <?= $email->isi_konfig ?>
                </li>
              </ul>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
              <h3>Tautan</h3>
              <ul class="list-unstyled">
                <li><a href="https://www.kemenkumham.go.id/">Portal Kemenkuham R.I.</a></li>
                <li><a href="https://www.bphn.go.id/">Portal BPHN</a></li>
                <li><a href="https://jdihn.go.id/">Portal JDIHN</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      </p>
      <div class="row justify-content-center text-center">
        <div class="col-md-7">
          <p class="copyright">&copy; Copyright SoftLand. All Rights Reserved</p>
          <div class="credits">
            <!--
            All the links in the footer should remain intact.
            You can delete the links only if you purchased the pro version.
            Licensing information: https://bootstrapmade.com/license/
            Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=SoftLand
          -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a><br>
            Customed by <a href="https://bphn.jdihn.go.id/">BPHN</a>
          </div>
        </div>
      </div>

    </div>
  </footer>
