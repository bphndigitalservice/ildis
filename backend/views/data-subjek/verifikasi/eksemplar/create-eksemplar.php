<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Tambah Data Eksemplar';
$this->params['breadcrumbs'][] = ['label' => 'Monografi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create-eksemplar', [
        'model' => $model,
        'id' => $id,
    ]) ?>
</div>