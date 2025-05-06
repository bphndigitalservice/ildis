<?php

use yii\helpers\Html;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Laporan Koleksi';
$this->params['breadcrumbs'][] = ['label' => 'Laporan', 'url' => ['koleksi']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs dashboard_tabs_cl">
        <li class="active"><a href="#tab_1" data-toggle="tab">Peraturan</a></li>
        <li><a href="#tab_2" data-toggle="tab">Monografi</a></li>
        <li><a href="#tab_3" data-toggle="tab">Artikel</a></li>
        <li><a href="#tab_4" data-toggle="tab">Putusan</a></li>
    </ul>
    
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <?= $this->render('_peraturan',['peraturan'=>$peraturan])?>
        </div>
        <div class="tab-pane" id="tab_2">

          <?= $this->render('_monografi',['monografi'=>$monografi])?>
      </div>
      <div class="tab-pane" id="tab_3">
       <?= $this->render('_artikel',['artikel'=>$artikel])?>
   </div> 

   <div class="tab-pane" id="tab_4">
       <?= $this->render('_putusan',['putusan'=>$putusan])?>
   </div>   


</div>

