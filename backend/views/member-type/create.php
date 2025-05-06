<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MemberType */

$this->title = 'Tambah Type Member';
$this->params['breadcrumbs'][] = ['label' => 'Member Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
