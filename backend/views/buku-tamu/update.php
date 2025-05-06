<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\BukuTamu $model */

$this->title = 'Update Buku Tamu: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Buku Tamus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="buku-tamu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
