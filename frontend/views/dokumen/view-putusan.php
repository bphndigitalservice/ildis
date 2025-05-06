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
$this->params['breadcrumbs'][] = htmlentities($this->title);
?>

<section>
    <br><br><br>
    <div class="container">
        <h1>Putusan Detail</h1>
        <div class="text-center">
            <!-- = Html::a('Home', ['/']); ?></p> -->
            <!-- = Html::a('Putusan', ['/dokumen/putusan']); ?></p> -->
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
                            <div class="col-lg-6 col-md-6 mb-3">
                                Klasifikasi<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->klasifikasi); ?></span>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                Amar Putusan<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->amar_status); ?></span>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                Tanggal Dibacakan <br>
                                <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->tanggal_penetapan); ?></span>
                            </div>

                            <div class="col-lg-6 col-md-6 mb-3">
                                Tingkat Proses<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->sub_klasifikasi); ?></span>
                            </div>

                            <div class="col-lg-6 col-md-6 mb-3">
                                Penggugat/Pemohon<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->pemohon); ?></span>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                Tergugat/Termohon<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->termohon); ?></span>
                            </div>

                            <div class="col-lg-6 col-md-6 mb-3">
                                Tempat Pengadilan<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->lembaga_peradilan); ?></span>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                Lokasi<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->tempat_terbit); ?></span>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                Bahasa<br>
                                <span class="text-extra-dark-gray font-weight-600"><?= htmlentities($model->bahasa); ?></span>
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
                            <li class="list-group-item text-center">AMAR PUTUSAN</li>
                            <li class="list-group-item list-group-item-danger text-center"><strong><?= htmlentities($model->amar_status); ?></strong></li>
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


                </div>
            </div>
            <!--  end blog right-->

        </div>
    </div>
</section>
