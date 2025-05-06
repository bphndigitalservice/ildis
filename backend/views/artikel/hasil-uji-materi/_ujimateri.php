<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">
    <?= Html::a('<i class="fa fa-plus-circle"></i> Tambah Uji Materi', ['tambah-hasil-uji-materi', 'id' => $id], ['class' => 'btn btn-success btn-flat']) ?>
    <p></p>
    <?= GridView::widget([
        'dataProvider' => $ujimateri,
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
                'label' => 'Hasil Uji Materi',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->hasil_uji_materi, ['download', 'id' => $data->hasil_uji_materi], ['target' => '_blank']);
                }
            ],
            [
                'label' => 'Status',
                'value' => 'status_hasum',
            ],

            [
                'label' => 'Catatan',
                'value' => 'catatan_hasum',
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
                        return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['ubah-hasil-uji-materi', 'id' => $model->id], ['title' => 'Ubah']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['hapus-hasil-uji-materi', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>