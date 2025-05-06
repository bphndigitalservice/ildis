<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Tambah Data Tajuk Entri Utama';
$this->params['breadcrumbs'][] = ['label' => 'Artikel', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create-teu', [
        'model' => $model,
        'id' => $id,
    ]) ?>
</div>