<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\LogPustakawan */

$this->title = 'Tambah Data Log Pustakawan';
$this->params['breadcrumbs'][] = ['label' => 'Log Pustakawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


