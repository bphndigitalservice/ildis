<?php

use yii\helpers\Html;
use backend\models\FrontendConfig;
$strukturbaghukum = FrontendConfig::findOne(20);
?>

<section>
    <img src="frontend/assets/img/bluetop.jpg" class="img-fluid" alt="">
   <br><br><br>
    <div class="container">
    </div>

    <div class="container">
        <div class="text-center">
            <p><?= Html::a('Home', ['/']); ?></p>
            <p><span class="active">Tentang Kami</span>
            </p>
        </div>
    </div>
    <br><br>
    <div class="container">
    <!-- <h4>Struktur Organisasi JDIH BPHN</h4> -->
         <!-- ?= Html::img('@web/frontend/assets/img/jdih/sto_kemenkumham.png', ['class' => 'width-100 margin-10px-bottom xs-margin-5px-bottom']); ?> -->
        <?= Html::img('@web/common/dokumen/' . $strukturbaghukum->isi_konfig, ['class' => 'width-100 margin-10px-bottom xs-margin-5px-bottom']); ?> -->
  </div>
</section>


