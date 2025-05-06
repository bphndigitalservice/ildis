<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KalaTerbit */

$this->title = 'Ubah Data Kala Terbit: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Kala Terbit', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>
