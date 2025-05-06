<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\BidangHukum */

$this->title = 'Tambah Data Bidang Hukum';
$this->params['breadcrumbs'][] = ['label' => 'Bidang Hukum', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


