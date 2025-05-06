<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Member */

$member = \backend\models\Member::findOne($model->id);


$this->title = 'Cari Buku untuk Member ' . $member->username;
$this->params['breadcrumbs'][] = ['label' => 'Peminjaman', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Pinjam Buku';



$id2  = $model->id;
?>


<div class="box-body table-responsive no-padding">

    <div class="row">
        <div class="col-md-6">

            <div class="box box-success box-solid">
                <div class="box-header with-border">

                    <b>Detail Data Member</b>
                </div>

                <div class="box-body">

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [

                            'username',
                            //'status',
                            'member_name',
                            'gender',
                            'birth_date',
                            //'member_type_id',
                            'member_address:ntext',
                            'member_email:email',
                            // 'postal_code',
                            //'personal_id_number',
                            ///'inst_name',

                            //'expire_date',
                            //'phone_number',
                            //'fax_number',

                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">

            <div class="box box-danger box-solid">
                <div class="box-header with-border">

                    <b>Detail Peminjaman</b>
                </div>

                <div class="box-body">
                    <?php
                    if (!empty($model2)) {
                        echo '
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Judul Buku</th>
                                <th scope="col">Nomor Eksemplar</th>
                                <th scope="col">Tanggal Pinjam</th>
                                <th scope="col">Tanggal Kembali</th>
                                <th scope="col">Aksi</th>
                            
                        </thead>
                        <tbody>';
                        foreach ($model2 as $data) {

                            echo '</tr>';
                            echo '<th scope="col">' . $data->id . '</th>';
                            echo '<th scope="col">' . $data->getJudul($data->document_id) . '</th>';
                            echo '<th scope="col">' . $data->item_code . '</th>';
                            echo '<th scope="col">' . $data->tanggal_pinjam . '</th>';
                            echo '<th scope="col">' . $data->tanggal_kembali . '</th>';
                            echo '<th scope="col">' .
                                Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['hapus-sirkulasi', 'id' => $data->id], [
                                    //'class' => 'btn btn-danger btn-flat',
                                    'data' => [
                                        'confirm' => 'Yakin menghapus data ini?',
                                        'method' => 'post',
                                    ],
                                ])

                                . '</th>';
                            echo '</tr>';
                        }
                        echo '
                        </tbody>
                    </table>';
                    }
                    ?>

                </div>

            </div>
            Catatan : penghapus hanya untuk kesalahan penginputan buku, bukan pengembalian buku!!!
        </div>
    </div>


    <div class="box box-primary box-solid">
        <div class="box-header with-border">

            <b>Form Cari Buku Peminjaman</b>
        </div>
        <div class="box-body">

            <?= GridView::widget([
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
                    [
                        'attribute' => 'id_dokumen',
                        'label' => 'Judul Buku',
                        'value' => 'monografi.judul',
                    ],
                    'kode_eksemplar',
                    'no_panggil',
                    'status_eksemplar',
                    //'kode_inventaris',
                    // [
                    //     'label' => 'URL',
                    //     'value' => function ($data) {
                    //         return $data->gePinjam($data->id, $id);
                    //     }
                    // ],

                    // [
                    //     'label' => 'url',
                    //     'format' => 'raw',
                    //     'value' => function ($data) {
                    //         $url = Url::to(['pinjam', 'id' => $data->id, 'id2' => $id]);
                    //         return Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-search-plus"></b> Pinjam Buku</span>', $url);
                    //     },
                    // ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                        'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                        'header' => 'Aksi',
                        'template' => Helper::filterActionColumn('{pinjam}'),
                        'buttons' => [
                            // 'pinjam' => function ($url, $model, $key) {
                            //     return
                            //         Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-search-plus"></b> Pinjam Buku</span>', ['pinjam', 'id' => $model->id, 'id' => $id], ['title' => 'pinjam buku']);
                            // },
                            // 'pinjam' => function ($url, $model) {
                            //     $url = Url::to(['pinjam', 'id' => $model->id, 'id2' => $id]);
                            //     //return Html::a('<span class="fa fa-pencil"></span>', $url, ['title' => 'update']);
                            //     return Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-search-plus"></b> Pinjam Buku</span>', $url, ['title' => 'ubah data']);
                            // },

                            'pinjam' => function ($url, $model) use ($id2) {
                                $url .= '&id2=' . $id2; //This is where I want to append the $lastAddress variable.
                                return Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-pencil"></b> Pinjam Buku</span>', $url);
                            },

                        ],
                    ],
                ],
            ]); ?>

        </div>
    </div>

</div>