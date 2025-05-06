<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Inisialisasi */

$this->title = 'Tambah Data Inisialisasi';
$this->params['breadcrumbs'][] = ['label' => 'Inisialisasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


