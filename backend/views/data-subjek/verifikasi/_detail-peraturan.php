<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use mdm\admin\components\Helper;
?>

<div class="box-header">
    <?= Html::a('<i class="fa fa-mail-reply"></i> Kembali', ['index'], ['class' => 'btn btn-success btn-flat']) ?>
    <?= Html::a('<i class="fa fa-edit"></i> Buat Catatan', ['catatan-verifikasi/create', 'id' => $model->id], ['class' => 'btn btn-danger btn-flat']) ?>
    <?php
    if ($model->is_publish == 1) {
        if (Helper::checkRoute('unpublish-view')) {
            echo Html::a('<b class="fa fa-download"></b> Unpublish', ['unpublish-view', 'id' => $model->id], [
                'class' => 'btn btn-warning',
                'data-confirm' => 'Yakin unpublish peraturan ini?',
                'data-method' => 'post',
            ]);
        }
        //echo Html::a('<b class="fa fa-angle-double-left"></b> Unpublish Peraturan', ['unpublish-peraturan','id'=>$model->id], ['class' => 'btn btn-warning mb-3']);
    } else {
        if (Helper::checkRoute('publish-view')) {
            echo Html::a('<b class="fa fa-upload"></b> Publish', ['publish-view', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data-confirm' => 'Yakin mempublikasi peraturan ini?',
                'data-method' => 'post',
            ]);
        }
        //echo Html::a('<b class="fa fa-angle-double-right"></b> Publish Peraturan', ['publish-peraturan','id'=>$model->id], ['class' => 'btn btn-success mb-3']);
    }
    ?>
    <p></p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'jenis_peraturan',
            //'bentuk_peraturan',
            [
                'attribute' => 'singkatan_jenis',
                'label' => 'Singkatan Peraturan',
            ],

            'nomor_peraturan',

            [
                'attribute' => 'tahun_terbit',
                'label' => 'Tahun',
                ''
            ],

            [
                'attribute' => 'judul',
                'label' => 'Judul Peraturan',
            ],

            [
                'attribute' => 'tempat_terbit',
                'label' => 'Tempat Penetapan',
            ],
            [
                'label' => 'Tanggal Penetapan',
                'value' => $model->getTanggal($model->tanggal_penetapan),
            ],
            [
                'label' => 'Tanggal Pengundangan',
                'value' => $model->getTanggal($model->tanggal_pengundangan),
            ],

            'sumber:ntext',
            'bahasa:ntext',
            'bidang_hukum:ntext',


            // [
            //     'label' => 'Dokumen Lampiran',
            //     'format' => 'html',
            //     'value' => function ($data) {
            //         return Html::a($data->dokumen->dokumen_lampiran, ['download-peraturan', 'id' => $data->id], ['target' => '_blank']);
            //     }
            // ],


            [
                'label' => 'Dokumen Lampiran',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getLampiran($data->dokumen->dokumen_lampiran);
                    //return Html::a($data->dokumen->dokumen_lampiran, ['download-peraturan', 'id' => $data->id], ['target' => '_blank']);
                    //return Html::a(Yii::$app->baseUrl.'../common/dokumen/'.$data->dokumen->dokumen_lampiran, ['class'=>'','target' => '_blank']);
                    // return Html::a($data->dokumen->dokumen_lampiran, ['../common/dokumen/' . $data->dokumen->dokumen_lampiran], ['class'=>'', 'target' => '_blank', 'title' => 'lihat file']);
                }
            ],
            [
                'label' => 'Dokumen Abstrak',
                'format' => 'raw',
                'value' => function ($data) {
                    //return Html::a($data->abstrak, ['download-abstrak', 'id' => $data->abstrak], ['target' => '_blank']);
                    return Html::a($data->abstrak, ['../common/dokumen/' . $data->abstrak], ['target' => '_blank', 'title' => 'lihat file']);
                }
            ],
            // [
            //     'label' => 'Judul Lampiran',
            //     'value'=>function($data){
            //         return $data->dokumen->judul_lampiran;}
            // ], 

            [
                'attribute' => 'status',
                'label' => 'Status Peraturan',
            ],

            [
                'attribute' => 'status_terakhir',
                'label' => 'Keterangan Status',
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