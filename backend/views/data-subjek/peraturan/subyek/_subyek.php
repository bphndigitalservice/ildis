<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">
    <?= Html::a('<i class="fa fa-plus-circle"></i> Tambah Subyek', ['tambah-subyek', 'id' => $id], ['class' => 'btn btn-success btn-flat']) ?>
    <p></p>
    <?= GridView::widget([
        'dataProvider' => $subyek,
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
                'label' => 'Status',
                'value' => 'subyek',
            ],
            [
                'label' => 'Type Subjek',
                'value' => 'tipe_subyek',
            ],
            [
                'label' => 'Jenis Subjek',
                'value' => 'jenis_subyek',
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'header' => 'Aksi',
                'template' => '{update}&nbsp;&nbsp;{delete}',

                'buttons' => [

                    'update' => function ($id, $model) {
                        return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['ubah-subyek', 'id' => $model->id], ['title' => 'Ubah']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['hapus-subyek', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>