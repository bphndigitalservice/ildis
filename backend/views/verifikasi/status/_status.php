<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">

    <p></p>
    <?= GridView::widget([
        'dataProvider' => $status,
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
                'value' => 'status_peraturan',
            ],

            [
                'label' => 'Peraturan',
                'format' => 'html',
                'value' => function ($data) {
                    if (empty($data->peraturan->judul)) {
                        return '-';
                    } else {


                        return Html::a($data->peraturan->judul, ['view', 'id' => $data->id_dokumen_target], ['target' => '_blank']);
                    }
                }
            ],
            [
                'label' => 'catatan',
                'value' => 'catatan_status_peraturan',
            ],

            [
                'label' => 'Tanggal Perubahan',
                'value' => function ($data) {
                    return $data->getTanggal($data->tanggal_perubahan);
                }
            ],

        ],
    ]); ?>
</div>