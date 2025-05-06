<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TipeKataKunci */

$this->title = 'Tambah Data Tipe Kata Kunci';
$this->params['breadcrumbs'][] = ['label' => 'Tipe Kata Kunci', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


