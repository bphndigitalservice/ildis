<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Circulation */

$this->title = 'Ubah Data Circulation: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Circulation', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>
