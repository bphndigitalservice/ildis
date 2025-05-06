<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Gmd */

$this->title = 'Ubah Data Gmd: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Gmd', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>
