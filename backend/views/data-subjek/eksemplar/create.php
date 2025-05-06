<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Eksemplar */

$this->title = 'Tambah Data Eksemplar';
$this->params['breadcrumbs'][] = ['label' => 'Eksemplar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


