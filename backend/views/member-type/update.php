<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MemberType */

$this->title = 'Ubah Type Member: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Member Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'member_type_name' => $model->member_type_name, 'loan_limit' => $model->loan_limit, 'loan_periode' => $model->loan_periode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="member-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
