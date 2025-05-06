<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PolaEksemplar */

$this->title = 'Tambah Data Pola Eksemplar';
$this->params['breadcrumbs'][] = ['label' => 'Pola Eksemplar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


