<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Saran */

$this->title = 'Tambah Data Saran';
$this->params['breadcrumbs'][] = ['label' => 'Saran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


