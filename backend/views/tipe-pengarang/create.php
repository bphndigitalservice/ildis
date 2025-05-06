<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TipePengarang */

$this->title = 'Tambah Data Tipe Pengarang';
$this->params['breadcrumbs'][] = ['label' => 'Tipe Pengarang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


