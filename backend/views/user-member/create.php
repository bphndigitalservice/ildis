<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserMember */

$this->title = 'Tambah Data User Member';
$this->params['breadcrumbs'][] = ['label' => 'User Member', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body no-padding">

    <?= $this->render('_form-create', [
    'model' => $model,
    ]) ?>
</div>


