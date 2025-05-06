<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Klasifikasi */

$this->title = 'Tambah Data Klasifikasi';
$this->params['breadcrumbs'][] = ['label' => 'Klasifikasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


