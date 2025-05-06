<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Tambah Data Subyek';
$this->params['breadcrumbs'][] = ['label' => 'Monografi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create-subyek', [
        'model' => $model,
    ]) ?>
</div>