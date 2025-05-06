<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Kabupaten */

$this->title = 'Tambah Data Kabupaten';
$this->params['breadcrumbs'][] = ['label' => 'Kabupaten', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


