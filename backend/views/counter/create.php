<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PcounterUsers $model */

$this->title = 'Create Pcounter Users';
$this->params['breadcrumbs'][] = ['label' => 'Pcounter Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pcounter-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
