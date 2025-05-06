<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UrusanPemerintahan */

$this->title = 'Ubah Data Urusan Pemerintahan: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Urusan Pemerintahan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>
