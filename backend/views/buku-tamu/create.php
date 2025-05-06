<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\BukuTamu $model */

$this->title = 'Create Buku Tamu';
$this->params['breadcrumbs'][] = ['label' => 'Buku Tamus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


