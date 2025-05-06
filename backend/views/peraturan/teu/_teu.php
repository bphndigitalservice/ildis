<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>
<div class="box-header">
    <?= Html::a('<i class="fa fa-plus-circle"></i> Tambah TEU', ['tambah-pengarang', 'id' => $id], ['class' => 'btn btn-success btn-flat']) ?>
    <p></p>
    <?= GridView::widget([
        'dataProvider' => $teu,
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
                'label' => 'Nama T.E.U',
                'value' => 'pengarang.name',
            ],

            [
                'label' => 'Type T.E.U',
                'value' => 'tipePengarang.name',
            ],

            [
                'label' => 'Jenis T.E.U',
                'value' => 'jenisPengarang.name',
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
            //'status',
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'header' => 'Aksi',
                'template' => '{delete}',
                'buttons' => [
                    'update' => function ($id, $model) {
                        return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['tambah-pengarang', 'id' => $model->id], ['title' => 'Ubah']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['hapus-pengarang', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>