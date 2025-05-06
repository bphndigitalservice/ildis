<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">
    <?= Html::a('<i class="fa fa-plus-circle"></i> Tambah Eksemplar', ['tambah-eksemplar', 'id' => $id], ['class' => 'btn btn-success btn-flat']) ?>
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
            [
                'attribute' => 'created_by',
                'value' => function ($data) {
                    return $data->getUserInput($data->created_by);
                },
            ],

            [
                'attribute' => 'updated_by',
                'value' => function ($data) {
                    return $data->getUserInput($data->updated_by);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'header' => 'Aksi',
                'template' => '{update}&nbsp;&nbsp;{delete}',

                'buttons' => [

                    'update' => function ($id, $model) {
                        return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['ubah-eksemplar', 'id' => $model->id], ['title' => 'Ubah']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['hapus-eksemplar', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>