<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">
    <?= Html::a('<i class="fa fa-plus-circle"></i> Tambah Lampiran', ['tambah-lampiran', 'id' => $id], ['class' => 'btn btn-success btn-flat']) ?>
    <p></p>
    <?= GridView::widget([
        'dataProvider' => $lampiran,
        'summary' => 'Ditampilkan {begin} - {end} dari {totalCount} Data',
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 50px;', 'class' => 'text-center'],
                'header' => 'No',
                'headerOptions' => ['class' => 'text-center'],
            ],

            [
                'label' => 'Deskripsi Lampiran',
                'value' => 'deskripsi_lampiran',
            ],
            [
                'label' => 'Judul Lampiran',
                'value' => 'judul_lampiran',
            ],
            [
                'label' => 'Dokumen Lampiran',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->dokumen_lampiran, ['download', 'id' => $data->dokumen_lampiran], ['target' => '_blank']);
                }
            ],
            [
                'label' => 'URL Lampiran',
                'value' => 'url_lampiran',
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($data) {
                    return $data->getUserInput($data->_created_by);
                },
            ],

            [
                'attribute' => 'updated_by',
                'value' => function ($data) {
                    return $data->getUserInput($data->_updated_by);
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
                        return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['ubah-lampiran', 'id' => $model->id], ['title' => 'Ubah']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['hapus-lampiran', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>