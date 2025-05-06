<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Member */

$this->title = 'Ubah Data Member: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Member', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>
