<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
?>

<div class="box-header">
    <?= Html::a('<i class="fa fa-mail-reply"></i> Kembali', ['index'], ['class' => 'btn btn-success btn-flat']) ?>
    <?= Html::a('<i class="fa fa-pencil"></i> Ubah Data Utama', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>

    <?= Html::a('<i class="fa fa-pencil"></i> Ubah Dokumen', ['ubah-lampiran', 'id' => $model->id], ['class' => 'btn btn-danger btn-flat']) ?>
    <p></p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'jenis_peraturan',
                'label' => 'Jenis Artikel',
            ],


            [
                'attribute' => 'tahun_terbit',
                'label' => 'Tahun',
            ],

            [
                'attribute' => 'judul',
                'label' => 'Judul Peraturan',
            ],

            'tanggal_penetapan',
            'sumber:ntext',
            'bahasa:ntext',
            'bidang_hukum:ntext',

            [
                'label' => 'Dokumen Lampiran',
                'format' => 'html',
                'value' => function ($data) {
                	if (empty($data->dokumen->dokumen_lampiran)){
                		return '-';
                	}else{
                    //return Html::a($data->dokumen->dokumen_lampiran, ['download-peraturan', 'id' => $data->id], ['target' => '_blank']);
                    return Html::a($data->dokumen->dokumen_lampiran, ['../common/dokumen/' . $data->dokumen->dokumen_lampiran], ['target' => '_blank', 'title' => 'lihat file']);
                }}
            ],

            [
                'label' => 'Dokumen Abstrak',
                'format' => 'html',
                'value' => function ($data) {               
                    return Html::a($data->abstrak, ['download-abstrak', 'id' => $data->abstrak], ['target' => '_blank']);
                }
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