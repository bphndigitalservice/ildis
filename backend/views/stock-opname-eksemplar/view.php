<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\StockOpnameEksemplar */
?>
<div class="stock-opname-eksemplar-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'eksemplar_id',
            'tanggal',
            'dokumen_id',
            'tahun',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
