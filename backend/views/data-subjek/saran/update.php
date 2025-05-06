<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Saran */

$this->title = 'Ubah Data Saran: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Saran', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>
