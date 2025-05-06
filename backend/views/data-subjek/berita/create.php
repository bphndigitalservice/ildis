<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Berita */

$this->title = 'Tambah Data Berita';
$this->params['breadcrumbs'][] = ['label' => 'Berita', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


