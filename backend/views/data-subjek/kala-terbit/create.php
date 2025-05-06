<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\KalaTerbit */

$this->title = 'Tambah Data Kala Terbit';
$this->params['breadcrumbs'][] = ['label' => 'Kala Terbit', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


