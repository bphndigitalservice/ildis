<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TipeDokumen */

$this->title = 'Tambah Data Tipe Dokumen';
$this->params['breadcrumbs'][] = ['label' => 'Tipe Dokumen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


