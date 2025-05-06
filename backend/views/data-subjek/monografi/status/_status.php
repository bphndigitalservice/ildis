<?php
use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">
    <?= Html::a('<i class="fa fa-plus-circle"></i> Tambah Status', ['tambah-status','id'=>$id], ['class' => 'btn btn-success btn-flat']) ?>
    <p></p>
    <?= GridView::widget([
        'dataProvider' => $status,
        'summary' => 'Ditampilkan {begin} - {end} dari {totalCount} Data',                
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 50px;', 'class' => 'text-center'],
                'header'=>'No',
                'headerOptions' => ['class' => 'text-center'],
            ],  
            
            [
                'label' =>'Status',
                'value' =>'status_peraturan',
            ],                      

            [
                'label' =>'Peraturan',
                'format' =>'html',
                'value' => function($data){
                return Html::a($data->peraturan->judul, ['view','id'=>$data->id_dokumen_target], ['target'=>'_blank']);
                }
            ],              
            [
                'label' =>'catatan',
                'value' =>'catatan_status_peraturan',
            ],               
             
            [
                'label' =>'Tanggal Perubahan',
                'value' =>function($data){
                    return $data->getTanggal($data->tanggal_perubahan);
                }
            ],               
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                'header' =>'Aksi',
                'template' => '{update}&nbsp;&nbsp;{delete}',
                
                'buttons' => [

                    'update' => function($id, $model) {
                                return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['ubah-status', 'id' => $model->id], ['title' => 'Ubah']);
                            },
                    'delete' => function($url, $model) {
                                return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['hapus-status', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                            },
                ],
            ],                
        ],
    ]); ?>            
</div>