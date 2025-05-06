<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Ubah Dokumen Abstrak';
$this->params['breadcrumbs'][] = ['label' => 'Peraturan', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->bentuk_peraturan, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update Dokumen';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update-abstrak', [
        'model' => $model,
    ]) ?>

</div>