<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Kecamatan */

$this->title = 'Ubah Data Kecamatan: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Kecamatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>
