<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UrusanPemerintahan */

$this->title = 'Tambah Data Urusan Pemerintahan';
$this->params['breadcrumbs'][] = ['label' => 'Urusan Pemerintahan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


