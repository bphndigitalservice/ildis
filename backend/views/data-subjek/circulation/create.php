<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Circulation */

$this->title = 'Tambah Data Circulation';
$this->params['breadcrumbs'][] = ['label' => 'Circulation', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


