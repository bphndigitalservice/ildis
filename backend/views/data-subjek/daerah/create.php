<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Daerah */

$this->title = 'Tambah Data Daerah';
$this->params['breadcrumbs'][] = ['label' => 'Daerah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


