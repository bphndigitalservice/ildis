<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Kecamatan */

$this->title = 'Tambah Data Kecamatan';
$this->params['breadcrumbs'][] = ['label' => 'Kecamatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


