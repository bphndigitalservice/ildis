<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\JenisPengarang */

$this->title = 'Tambah Data Jenis Pengarang';
$this->params['breadcrumbs'][] = ['label' => 'Jenis Pengarang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


