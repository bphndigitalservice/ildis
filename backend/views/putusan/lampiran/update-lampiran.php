<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Ubah Data Lampiran';
$this->params['breadcrumbs'][] = ['label' => 'Putusan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-update-lampiran', [
        'model' => $model,
    ]) ?>
</div>