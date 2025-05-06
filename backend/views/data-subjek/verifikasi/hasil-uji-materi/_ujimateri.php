<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">

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

        ],
    ]); ?>
</div>