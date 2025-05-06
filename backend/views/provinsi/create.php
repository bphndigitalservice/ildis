<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Provinsi */

$this->title = 'Tambah Data Provinsi';
$this->params['breadcrumbs'][] = ['label' => 'Provinsi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


