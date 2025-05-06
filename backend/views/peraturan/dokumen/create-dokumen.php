<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Tambah Dokumen Terkait';
$this->params['breadcrumbs'][] = ['label' => 'Peraturan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create-dokumen', [
        'model' => $model,
    ]) ?>
</div>