<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Penerbit */

$this->title = 'Ubah Data Penerbit: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Penerbit', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>
