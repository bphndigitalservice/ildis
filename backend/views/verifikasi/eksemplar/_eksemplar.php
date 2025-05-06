<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">

    <p></p>
    <?= GridView::widget([
        'dataProvider' => $eksemplar,
        'summary' => 'Ditampilkan {begin} - {end} dari {totalCount} Data',
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 50px;', 'class' => 'text-center'],
                'header' => 'No',
                'headerOptions' => ['class' => 'text-center'],
            ],

            'kode_eksemplar',
            'no_panggil',
            'kode_inventaris',

            'lokasi_rak',
            'tipe_lokasi',
            'status_eksemplar',
            'nomor_pemesanan',
            'tgl_pemesanan',
            'tgl_penerimaan',
            'agen',
            'sumber_perolehan',
            'faktur',
            'tgl_faktur',
            'harga',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',


        ],
    ]); ?>
</div>