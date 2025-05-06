<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PcounterUsers $model */

$this->title = 'Update Pcounter Users: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pcounter Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pcounter-users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
