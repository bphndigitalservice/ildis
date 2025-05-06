<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DataSubyek */

$this->title = 'Tambah Data Data Subyek';
$this->params['breadcrumbs'][] = ['label' => 'Data Subyek', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


