<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PolaEksemplar */

$this->title = 'Ubah Data Pola Eksemplar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pola Eksemplar', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>
