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

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dokumens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="page-title-section bg-img cover-background" data-overlay-dark="7" data-background="img/banner/bg1.jpg">
    <div class="container">
        <h1>Blog Details</h1>
        <ul class="text-center">
            <li><?= Html::a('Home', ['/']); ?></li>
            <li>
                <span class="active">Blog Details</span>
            </li>
        </ul>
    </div>
</section>
<!-- end page title section -->

<!-- start blog detail section -->
<section class="blogs">
    <div class="container">
        <div class="widget search">

            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="cari dokumen hukum lain..." aria-label="Recipient's username" aria-describedby="button-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" id="button-addon2"><span class="ti-search"></span></button>
                </div>
            </div>
        </div>
        <div class="row">
            <!--  start blog left-->

            <div class="col-lg-8 col-md-12 sm-margin-50px-bottom">
                <div class="posts">
                    <!--  start post-->

                    <div class="content">
                        <div class="blog-list-simple-text post-meta margin-20px-bottom">
                            <div class="post-title">
                                <h5><?= $model->judul; ?></h5>
                            </div>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-lg-6 col-md-6 mb-3">
                                Tempat Terbit<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= $model->tempat_terbit; ?></span>
                            </div>

                            <div class="col-lg-6 col-md-6 mb-3">
                                Tanggal Penetapan <br>
                                <span class="text-extra-dark-gray font-weight-600"><?= $model->tanggal_penetapan; ?></span>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                Tanggal Pengundangan<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= $model->tanggal_pengundangan; ?></span>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                Sumber<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= $model->sumber; ?></span>
                            </div>

                            <div class="col-lg-6 col-md-6 mb-3">
                                Urusan Pemerintahan<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= $model->urusan_pemerintahan; ?></span>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                Bidang Hukum<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= $model->bidang_hukum; ?></span>
                            </div>

                            <div class="col-lg-6 col-md-6 mb-3">
                                Bahasa<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= $model->bahasa; ?></span>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                Pemrakarsa<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= $model->pemrakarsa; ?></span>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                Penandatanganan<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= $model->penandatanganan; ?></span>
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
                                        echo '<li class="list-group-item">' . $data['status_perter'];
                                        echo ' : ';
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
                                            echo '<tr><td>' . $data->namaPengarang->name . '</td>';
                                            echo '<td>' . $data->tipePengarang->name . '</td>';
                                            echo '<td>' . $data->jenisPengarang->name . '</td></tr>';
                                        }
                                        echo '</tbody>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>

                        <div class="row align-items-end">
                            <div class="col-lg-12 col-md-12 mt-4">
                                <span class="text-extra-dark-gray font-weight-600">SUBJEK</span><br>
                                <table class="table">
                                    <thead>
                                        <tr class="active">
                                            <th>Nama Subjek</th>
                                            <th>Tipe Subjek</th>
                                            <th>Jenis Subjek</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $subjek = DataSubyek::find()->where(['id_dokumen' => $model->id])->all();
                                    if (!empty($subjek)) {
                                        echo '<tbody>';
                                        foreach ($subjek as  $data) {

                                            echo '<tr><td>' . $data->subyek . '</td>';
                                            echo '<td>' . $data->tipe_subyek . '</td>';
                                            echo '<td>' . $data->jenis_subyek . '</td></tr>';
                                        }
                                        echo '</tbody>';
                                    }
                                    ?>
                                </table>
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
                            <li class="list-group-item list-group-item-primary text-center"><strong><?= $model->bentuk_peraturan; ?></strong></li>
                        </ul>
                    </div>

                    <div class="shadow">
                        <ul class="list-group mt-2">
                            <li class="list-group-item text-center">STATUS</li>
                            <li class="list-group-item list-group-item-danger text-center"><strong><?= $model->status; ?></strong></li>
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
                                    echo Html::a($data['dokumen_lampiran'], ['/dokumen/download', 'id' => $data->dokumen_lampiran], ['class' => 'btn btn-secondary btn-sm mb-2 btn-hover-primary', 'title' => 'download file']);

                                    # code...
                                }
                            }
                            echo Html::a($model->abstrak, ['/dokumen/download', 'id' => $model->abstrak], ['class' => 'btn btn-secondary btn-sm mb-2 btn-hover-primary', 'title' => 'download file']);
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
                                    echo  '<span class="text-extra-dark-gray font-weight-600">' . $data['status_peraturan'] . '</span> ' . Html::a($data->getJudul($data['id_dokumen']), ['/dokumen/view', 'id' => $data->id_dokumen], ['class' => 'text-primary', 'title' => 'lihat detail']) . '<br><br>';

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