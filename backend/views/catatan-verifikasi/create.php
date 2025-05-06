<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CatatanVerifikasi */

$this->title = 'Tambah Catatan Verifikasi';
$this->params['breadcrumbs'][] = ['label' => 'Catatan Verifikasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catatan-verifikasi-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>