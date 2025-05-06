<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TipeKoleksi */

$this->title = 'Tambah Data Tipe Koleksi';
$this->params['breadcrumbs'][] = ['label' => 'Tipe Koleksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


