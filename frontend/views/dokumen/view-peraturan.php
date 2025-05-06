<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\PeraturanTerkait;
use frontend\models\DokumenTerkait;
use frontend\models\DataLampiran;
use frontend\models\DataStatus;
use frontend\models\HasilUjiMateri;
use frontend\models\DataPengarang;
use frontend\models\DataSubyek;
/* @var $this yii\web\View */
/* @var $model frontend\models\Dokumen */

$this->title = $title;
$this->description = $deskripsi;

$this->keywords = $keywords;
$this->params['breadcrumbs'][] = ['label' => 'Dokumens', 'url' => ['index']];
$this->params['breadcrumbs'][] = htmlentities($this->title);
?>

<section>
    <br><br><br>
    <div class="container">
        <h1>Peraturan Detail</h1>
        <div class="text-center">
            <!-- = Html::a('Home', ['/']); ?></p> -->
            <!-- = Html::a('Peraturan', ['/dokumen/peraturan']); ?></p> -->
            <!-- <p>
                <span class="active"> <?= $model->jenis_peraturan; ?></span>
            </p> -->
        </div>
    </div>
    <!-- end page title section -->

    <!-- start blog detail section -->
    <section class="blogs">
        <div class="container">
            <div class="widget search mb-4">
                <form id="w0" action="/dokumen/index" method="get" data-pjax="1">
                    <div class="form-row align-items-center">
                        <div class="col-md-10 my-1">
                            <input type="text" class="form-control" id="dokumensearch-judul" name="DokumenSearch[judul]" placeholder="cari dokumen hukum lainnya...">
                        </div><br>
                        <div class="col-md-2 my-1">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <!--  start blog left-->

                <div class="col-lg-8 col-md-12 sm-margin-50px-bottom">
                    <div class="posts">
                        <!--  start post-->

                        <div class="content">
                            <div class="blog-list-simple-text post-meta margin-20px-bottom">
                                <div class="post-title">
                                    <h5><?= htmlentities($model->judul); ?></h5>
                                </div>
                            </div>
                            <div class="row align-items-end">


                                <?php
                                switch ($model->jenis_peraturan) {
                                    case 'STATUTEN':
                                    case 'STAATSBLAD':
                                        echo '
                                <div class="col-lg-6 col-md-6 mb-3">
                                Nomor<br>
                                <span class="text-extra-dark-gray font-weight-600">' . htmlentities($model->nomor_peraturan) . '</span>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                Tahun<br>
                                <span class="text-extra-dark-gray font-weight-600">' . htmlentities($model->tahun_terbit) . '</span>
                                </div>
                                ';
                                        break;
                                }
                                ?>



                                <div class="col-lg-6 col-md-6 mb-3">
                                    Nomor<br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->nomor_peraturan); ?></span>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    Tahun<br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->tahun_terbit); ?></span>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    Tempat Terbit<br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->tempat_terbit); ?></span>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    Tanggal Penetapan <br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= $model->getTanggal($model->tanggal_penetapan); ?></span>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    Tanggal Pengundangan<br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->tanggal_pengundangan); ?></span>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    Sumber<br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->sumber); ?></span>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    Urusan Pemerintahan<br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->urusan_pemerintahan); ?></span>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    Bidang Hukum<br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->bidang_hukum); ?></span>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    Bahasa<br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->bahasa); ?></span>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    Lokasi<br>
                                    <span class="text-extra-dark-gray font-weight-600"></span>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    Pemrakarsa<br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->pemrakarsa); ?></span>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    Penandatanganan<br>
                                    <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->penandatanganan); ?></span>
                                </div>
                            </div>

                            <div class="row align-items-end">

                                <div class="col-lg-12 col-md-12 mt-3">
                                    <span class="text-extra-dark-gray font-weight-600">Peraturan Terkait</span><br>
                                    <?php
                                    $peraturanterkait = PeraturanTerkait::find()->where(['id_dokumen' => $model->id])->all();

                                    if (!empty($peraturanterkait)) {
                                        echo '<ul>';
                                        foreach ($peraturanterkait as  $data) {
                                            // echo '<li class="list-group-item">' . $data['status_perter'];
                                            //echo ' : ';
                                            echo '<li class="list-group-item">';
                                            //echo $data->getJudul($data['peraturan_terkait']);
                                            echo Html::a($data->getJudul($data['peraturan_terkait']), ['/dokumen/view', 'id' => $data->peraturan_terkait], ['class' => 'text-primary', 'title' => 'lihat detail']);
                                            echo '</li>';
                                            # code...
                                        }
                                    } else {
                                        echo '<span class="text-extra-dark-gray font-weight-600">Data Tidak Tersedia</span>';
                                    }
                                    echo '</ul>';
                                    ?>
                                </div>
                            </div>

                            <div class="row align-items-end">
                                <div class="col-lg-12 col-md-12 mt-4">
                                    <span class="text-extra-dark-gray font-weight-600">Dokumen Terkait</span><br>
                                    <?php
                                    $dokumenterkait = DokumenTerkait::find()->where(['id_dokumen' => $model->id])->all();

                                    if (!empty($dokumenterkait)) {
                                        foreach ($dokumenterkait as  $data) {
                                            //echo $data['document_terkait'];
                                            echo Html::a($data['document_terkait'], ['/dokumen/download', 'id' => $data->document_terkait], ['class' => 'btn btn-secondary btn-sm mb-2 btn-hover-primary', 'title' => 'download file']);

                                            echo '<br>';
                                            # code...
                                        }
                                    } else {
                                        echo '<span class="text-extra-dark-gray font-weight-600">Data belum Tersedia</span>';
                                    }
                                    // echo '</ul>';

                                    ?>
                                </div>
                            </div>

                            <div class="row align-items-end">
                                <div class="col-lg-12 col-md-12 mt-4">
                                    <span class="text-extra-dark-gray font-weight-600">Hasil Uji Materi</span><br>
                                    <?php
                                    $ujimateri = HasilUjiMateri::find()->where(['id_dokumen' => $model->id])->all();

                                    if (!empty($ujimateri)) {
                                        foreach ($ujimateri as  $data) {
                                            //echo $data['document_terkait'];
                                            echo Html::a($data['hasil_uji_materi'], ['/dokumen/download', 'id' => $data->hasil_uji_materi], ['class' => 'btn btn-secondary btn-sm mb-2 btn-hover-primary', 'title' => 'download file']);

                                            echo '<br>';
                                            # code...
                                        }
                                    } else {
                                        echo '<span class="text-extra-dark-gray font-weight-600">Data belum Tersedia</span>';
                                    }
                                    // echo '</ul>';

                                    ?>
                                </div>
                            </div>

                            <div class="row align-items-end">
                                <div class="col-lg-12 col-md-12 mt-4">
                                    <span class="text-extra-dark-gray font-weight-600">T.E.U BADAN</span><br>
                                    <table class="table">
                                        <thead>
                                            <tr class="active">
                                                <th>Nama Pengarang</th>
                                                <th>Tipe Pengarang</th>
                                                <th>Jenis Pengarang</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $teu = DataPengarang::find()->where(['id_dokumen' => $model->id])->all();
                                        if (!empty($teu)) {
                                            echo '<tbody>';
                                            foreach ($teu as  $data) {
                                                //echo $data['document_terkait'];
                                                echo '<tr><td>' . htmlentities($data->namaPengarang->name) . '</td>';
                                                echo '<td>' . htmlentities($data->tipePengarang->name) . '</td>';
                                                echo '<td>' . htmlentities($data->jenisPengarang->name) . '</td></tr>';
                                            }
                                            echo '</tbody>';
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>

                            <div class="row align-items-end">
                                <div class="col-lg-12 col-md-12 mt-4">
                                    <span class="text-extra-dark-gray font-weight-600"> SUBJEK : </span>
                                    <!-- <table class="table">
                                    <thead>
                                        <tr class="active">
                                            <th>Nama Subjek</th>
                                            <th>Tipe Subjek</th>
                                            <th>Jenis Subjek</th>
                                        </tr>
                                    </thead> -->
                                    <?php
                                    $subjek = DataSubyek::find()->where(['id_dokumen' => $model->id])->all();
                                    if (!empty($subjek)) {
                                        echo '<tbody>';
                                        foreach ($subjek as  $data) {

                                            echo  htmlentities($data->subyek) . ' - ';
                                        }
                                        //echo// '</tbody>';
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  end blog left-->

                <!--  start blog right-->
                <div class="col-lg-4 col-md-12 padding-30px-left sm-padding-15px-left">
                    <div class="side-bar">



                        <div class="shadow">
                            <ul class="list-group mt-2">
                                <li class="list-group-item text-center">JENIS DOKUMEN</li>
                                <li class="list-group-item list-group-item-primary text-center"><strong><?= htmlentities($model->jenis_peraturan); ?></strong></li>
                            </ul>
                        </div>
                          
                        <div class="shadow">
                            <ul class="list-group mt-2">
                                <li class="list-group-item text-center">SINGKATAN JENIS</li>
                                <li class="list-group-item list-group-item-primary text-center"><strong><?= htmlentities($model->singkatan_jenis); ?></strong></li>
                            </ul>
                        </div>

                        <div class="shadow">
                            <ul class="list-group mt-2">
                                <li class="list-group-item text-center">STATUS</li>
                                <li class="list-group-item list-group-item-danger text-center"><strong><?= htmlentities($model->status); ?></strong></li>
                            </ul>
                        </div>



                        <div class="widget">
                            <div class="widget-title margin-35px-bottom mt-4">
                                <h3>Lampiran</h3>
                            </div>
                            <ul class="widget-list">
                                <?php
                                $lampiran = DataLampiran::find()->where(['id_dokumen' => $model->id])->all();

                                if (!empty($lampiran)) {
                                    foreach ($lampiran as  $data) {
                                        //echo '<li>'.$data['dokumen_lampiran'].'</li>';
                                        //echo Html::a($data['dokumen_lampiran'], ['/dokumen/download', 'id' => $data->dokumen_lampiran], ['target' => '_blank', 'class' => 'btn btn-secondary btn-sm mb-2 btn-hover-primary', 'title' => 'download file']);
                                        echo  Html::a($data['dokumen_lampiran'], ['/common/dokumen/' . $data->dokumen_lampiran], ['class' => 'btn btn-secondary btn-sm mb-2 btn-hover-primary', 'target' => '_blank', 'title' => 'lihat file']);
                                        # code...
                                    }
                                }
                                if (!empty($model->abstrak)) {
                                    echo '<br>';
                                    //echo Html::a($model->abstrak, ['/dokumen/download', 'id' => $model->abstrak], ['target' => '_blank', 'class' => 'btn btn-secondary btn-sm mb-2 btn-hover-primary', 'title' => 'download file']);
                                    echo  Html::a($model->abstrak, ['/common/dokumen/' . $model->abstrak], ['class' => 'btn btn-secondary btn-sm mb-2 btn-hover-primary', 'target' => '_blank', 'title' => 'lihat file']);
                                }
                                ?>

                            </ul>
                        </div>

                        <div class="widget">
                            <div class="widget-title margin-35px-bottom mt-4">
                                <h3>Keterangan Status</h3>
                            </div>
                            <ul class="widget-list">
                                <?php
                                $status = DataStatus::find()->where(['id_dokumen' => $model->id])->all();

                                if (!empty($status)) {

                                    foreach ($status as  $data) {
                                        //echo '<li>'.$data['dokumen_lampiran'].'</li>';
                                        //echo '<li>'.Html::a($data['status_peraturan'], ['/dokumen/download','id'=>$data->id_dokumen], ['title' => 'download file']).'</li>';
                                        if (!empty($data->catatan_status_peraturan)) {
                                            $catatan = ' ( ' . $data->catatan_status_peraturan . ' ) ';
                                        } else {
                                            $catatan = '';
                                        }
                                        echo  '<span class="text-extra-dark-gray font-weight-600">' . $data['status_peraturan'] . '</span> ' . Html::a($data->getJudul($data['id_dokumen_target']), ['/dokumen/view', 'id' => $data->id_dokumen_target], ['class' => 'text-primary', 'title' => 'lihat detail']) . htmlentities($catatan) . '<br><br>';

                                        # code...
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </ul>
                        </div>

                    </div>
                </div>
                <!--  end blog right-->

            </div>
        </div>
    </section>
