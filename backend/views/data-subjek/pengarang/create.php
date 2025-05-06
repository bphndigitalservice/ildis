<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Pengarang */

$this->title = 'Tambah Data Pengarang';
$this->params['breadcrumbs'][] = ['label' => 'Pengarang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


