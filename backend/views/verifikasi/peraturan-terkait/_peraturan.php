<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">

    <p></p>
    <?= GridView::widget([
        'dataProvider' => $peraturan,
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
                'label' => 'Peraturan Terkait',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->peraturan->judul, ['view', 'id' => $data->peraturan_terkait], ['target' => '_blank']);
                }
            ],
            [
                'label' => 'Status',
                'value' => 'status_perter',
            ],

            [
                'label' => 'Catatan',
                'value' => 'catatan_perter',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'header' => 'Aksi',
                'template' => '{update}&nbsp;&nbsp;{delete}',

                'buttons' => [

                    'update' => function ($id, $model) {
                        return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['ubah-peraturan-terkait', 'id' => $model->id], ['title' => 'Ubah']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['hapus-peraturan-terkait', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>