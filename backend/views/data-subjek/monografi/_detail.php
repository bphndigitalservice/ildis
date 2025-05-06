<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
?>

<div class="box-header">
    <?= Html::a('<i class="fa fa-mail-reply"></i> Kembali', ['index'], ['class' => 'btn btn-success btn-flat']) ?>
    <?= Html::a('<i class="fa fa-pencil"></i> Ubah Data Utama', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>


    <p></p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'jenis_peraturan',
                'label' => 'Jenis Monografi',
            ],


            [
                'attribute' => 'judul',
                'label' => 'Judul Monografi',
            ],

            [
                'attribute' => 'tahun_terbit',
                'label' => 'Tahun',
            ],

            [
                'attribute' => 'penerbit',
                'label' => 'Penerbit',
            ],

            [
                'attribute' => 'tempat_terbit',
                'label' => 'Tempat Terbit',
            ],
'isbn',
            'nomor_panggil',
            'deskripsi_fisik',
            'klasifikasi:ntext',
        
[
'label'=>'Anotasi',
'attribute'=>'sumber',
],
            'bahasa:ntext',
'bidang_hukum',



            [
                'label' => 'Dokumen Abstrak',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->abstrak, ['download-abstrak', 'id' => $data->abstrak], ['target' => '_blank']);
                }
            ],
            // [
            //     'label' => 'Judul Lampiran',
            //     'value'=>function($data){
            //         return $data->dokumen->judul_lampiran;}
            // ], 


            [
                'label' => 'Cover',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::img(\Yii::getAlias('@imageurl') . '/common/dokumen/' . $data->gambar_sampul, ['alt' => 'myImage', 'width' => '300', 'height' => 'auto']);
                },
            ],

            [
                'attribute' => 'created_at',
                'value' => function ($data) {
                    return $data->getTanggal2($data->created_at);
                },
            ],

            [
                'attribute' => 'created_by',
                'value' => function ($data) {
                    return $data->getUserInput($data->_created_by);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($data) {
                    return $data->getTanggal2($data->updated_at);
                },
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($data) {
                    return $data->getUserInput($data->_updated_by);
                },
            ],
        ],
    ]) ?>
</div>