<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">
    <?php // Html::a('<i class="fa fa-plus-circle"></i> Tambah Dokumen Upload', ['tambah-dokumen-terkait','id'=>$id], ['class' => 'btn btn-success btn-flat']) 
    ?>


    <p></p>
    <?= GridView::widget([
        'dataProvider' => $dokumen,
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
                'label' => 'Dokumen Terkait',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->document_terkait, ['download', 'id' => $data->document_terkait], ['target' => '_blank']);
                }
            ],
            [
                'label' => 'Status',
                'value' => 'status_docter',
            ],

            [
                'label' => 'Catatan',
                'value' => 'catatan_docter',
            ],


        ],
    ]); ?>
</div>