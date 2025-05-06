<?php

use yii\helpers\Html;
use backend\models\FrontendConfig;
$misi = FrontendConfig::findOne(11); 
?>

<section>
    <img src="frontend/assets/img/bluetop.jpg" class="img-fluid" alt="">
   <br><br><br>
    <div class="container">
    </div>

    <div class="container">
        <div class="text-center">
            <!-- <p><?= Html::a('Home', ['/']); ?></p> -->
            <p><span class="active">Tentang Kami</span>
            </p>
        </div>
    </div>
    <br>
    <div class="container">
       <?= $misi->isi_konfig?>
   </div>
</section>
