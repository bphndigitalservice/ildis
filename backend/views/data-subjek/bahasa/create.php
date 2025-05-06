<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Bahasa */

$this->title = 'Tambah Data Bahasa';
$this->params['breadcrumbs'][] = ['label' => 'Bahasa', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


