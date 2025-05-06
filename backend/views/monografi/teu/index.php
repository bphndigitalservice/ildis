<?php

use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PeraturanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Monografi Hukum';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php Pjax::begin(['enablePushState' => false]); ?>

<div class="box-body table-responsive no-padding">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?= GridView::widget([

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Data Monografi Hukum</h3>',
        ],
        'toolbar' =>  [
            ['content' => Html::a('<i class="fa fa-plus-circle"></i> Tambah Data', ['create'], ['class' => 'btn btn-success'])],
            '{export}',
            '{toggleData}'
        ],
        'dataProvider' => $dataProvider,
        'summary' => 'Ditampilkan {begin} - {end} dari {totalCount} Data',
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [

            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 50px;', 'class' => 'text-center'],
                'header' => 'No',
                'headerOptions' => ['class' => 'text-center'],
            ],

            //'id',
            //'tipe_dokumen:ntext',
            //  'jenis_peraturan',

            [
                'attribute' => 'jenis_peraturan',
                'label' => 'Jenis Monografi',
                'headerOptions' => ['style' => 'width:200px'],
                'contentOptions' => ['style' => 'width:200; white-space: normal;'],
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => 'Pilih Jenis'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        //  'width'=>'resolve',
                    ],
                ],
                'filter' => ArrayHelper::map(\backend\models\JenisPeraturan::find()->where(['parent_id' => 2])->all(), 'name', 'name'),
                'value' => 'jenis_peraturan',
            ],





            [
                'attribute' => 'judul',
                'label' => 'Judul Putusan',
                'contentOptions' => ['style' => 'width:auto; white-space: normal;'],
                'content' => function ($data) {

                    return $data->getJudul($data->id);
                    //return Html::a(strtoupper($data->judul), ['view', 'id' => $data->id]);
                }
            ],

            // 'subjek',




            [
                'label' => 'Tahun',
                'attribute' => 'tahun_terbit',
            ],

            // [
            //     'label' => 'ISBN',
            //     'attribute' => 'isbn',
            // ],

            // [
            //     'label' => 'Penerbit',
            //     'attribute' => 'penerbit',
            // ],
            'sumber_perolehan',
            [
                'label' => 'Subyek',
                'content' => function ($data) {
                    return $data->getSubyek2($data->id);
                }

            ],

            // [
            //     'label' => 'Jumlah Buku',
            //     'content' => function ($data) {
            //         return $data->getJumlah($data->id);
            //     }

            // ],
            [
                'label' => 'Kode Eksemplar',
                'content' => function ($data) {
                    return $data->getEksemplar($data->id);
                }

            ],


            //'tanggal_penetapan',

            //'teu:ntext',
            //'nomor_peraturan',
            // 'nomor_panggil',
            // 'bentuk_peraturan:ntext',
            // 'singkatan_jenis:ntext',
            // 'cetakan',
            // 'tempat_terbit:ntext',
            // 'penerbit:ntext',
            // 'tanggal_penetapan',
            // 'deskripsi_fisik',
            // 'sumber:ntext',
            // 'isbn',
            // 'bahasa:ntext',
            // 'bidang_hukum:ntext',
            // 'nomor_induk_buku',
            // 'jenis_peraturan',
            // 'singkatan_bentuk',
            // 'tipe_koleksi_nomor_eksemplar',
            // 'pola_nomor_eksemplar',
            // 'jumlah_eksemplar',
            // 'kala_terbit',
            // 'tahun_terbit',
            // 'tanggal_dibacakan',
            // 'pernyataan_tanggung_jawab:ntext',
            // 'edisi',
            // 'gmd',
            // 'judul_seri',
            // 'klasifikasi',
            // 'info_detil_spesifik',
            // 'abstrak',
            // 'gambar_sampul',
            // 'label',
            // 'sembunyikan_di_opac',
            // 'promosikan_ke_beranda',
            // 'status_terakhir',
            // 'status',
            // '_created_by',
            // '_updated_by',
            // '_created_time',
            // '_updated_time',

            [


                'attribute' => 'is_publish',
                'label' => 'Status Verifikas',
                'filter' => [1 => 'Verified', 0 => 'UnVerified'],
                'format' => 'raw',
                'options' => [
                    'width' => '100px',
                ],
                'value' => function ($data) {
                    if ($data->is_publish == 1)
                        return "<span class='label label-primary'>" . 'Verified' . "</span>";
                    else
                        return "<span class='label label-danger'>" . 'UnVerified' . "</span>";
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 200px;', 'class' => 'text-center'],
                'contentOptions' => ['style' => 'width: 200px;', 'class' => 'text-center'],
                'header' => 'Aksi',
                'template' => Helper::filterActionColumn('{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}&nbsp;&nbsp;{action}'),

                'buttons' => [
                    'view' => function ($url, $model) {
                        return
                            Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-search-plus"></b></span>', ['view', 'id' => $model->id], ['title' => 'Lihat']);
                    },
                    'update' => function ($id, $model) {
                        return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['update', 'id' => $model->id], ['title' => 'Ubah']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['delete', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                    },
                    'action' => function ($url, $model) {
                        if ($model->is_publish == 0) {
                            return Html::a('<span class="fa fa-check-square"></span>', ['catatan-verifikasi/monografi', 'id' => $model->id], ['title' => 'Buat catatan', 'class' => 'btn btn-info btn-sm user-active', 'data' => ['confirm' => 'Ingin membuat catatan ?', 'method' => 'post', 'data-pjax' => false],]);
                        }
                    },

                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>