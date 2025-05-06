<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Ubah Dokumen Terkait';
$this->params['breadcrumbs'][] = ['label' => 'Peraturan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update-dokumen', [
        'model' => $model,
    ]) ?>
</div>