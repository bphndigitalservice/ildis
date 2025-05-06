<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">

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
                'label' => 'Fulltext',
                'value' => 'fulltext',
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


        ],
    ]); ?>
</div>