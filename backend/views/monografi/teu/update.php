<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Ubah Data Monografi: ' . $model->jenis_peraturan;
$this->params['breadcrumbs'][] = ['label' => 'Monografi', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->jenis_peraturan, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>