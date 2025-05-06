<?php
/* @var $this yii\web\View */


use yii\helpers\Html;
use yii\widgets\ListView;
?>

<section style="background-color: dodgerblue;" data-overlay-dark="7" data-background="/jdih/img/banner/header.jpg">
    <div class="container">
        <h1>Profile</h1>
        <ul class="text-center">
            <li><?= Html::a('Home', ['/']); ?></li>
            <li>
                <span class="active">Profile</span>
            </li>
        </ul>
    </div>
</section>


<section>
    <div class="container">
        <div class="row pb-5">

            <div class="col-md-5 col-lg-3 xs-margin-30px-bottom">
                <div class="summary padding-40px-all slow-redirect bg-light">
                    <h6>Dashboard</h6>
                    <ul class="list-unstyled no-margin-bottom">
                        <li><?= Html::a('Profile', ['profile/profile']) ?></li>
                        <li><?= Html::a('Peminjaman', ['profile/peminjaman']) ?></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-7 col-lg-9 padding-10px-top">
                <h4 id="alert">Peminjaman</h4>

                <div class="horizontaltab tab-style4">
                    <ul class="resp-tabs-list hor_1">
                        <li> Peminjaman Saat Ini </li>
                        <li>Histori Peminjaman</li>

                    </ul>
                    <div class="resp-tabs-container hor_1">
                        <div>

                            <?php
                            if (!empty($model)) {
                                echo '
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Judul Buku</th>
                                <th scope="col">Nomor Eksemplar</th>
                                <th scope="col">Tanggal Pinjam</th>
                                <th scope="col">Tanggal Kembali</th>
                                
                            
                        </thead>
                        <tbody>';
                                $i = 1;
                                foreach ($model as $data) {

                                    echo '</tr>';
                                    echo '<th scope="col">' . $i . '</th>';
                                    echo '<th scope="col">' . $data->getJudul($data->document_id) . '</th>';
                                    echo '<th scope="col">' . $data->item_code . '</th>';
                                    echo '<th scope="col">' . $data->tanggal_pinjam . '</th>';
                                    echo '<th scope="col">' . $data->tanggal_kembali . '</th>';

                                    echo '</tr>';
                                    $i++;
                                }
                                echo '
                        </tbody>
                    </table>';
                            }
                            ?>
                        </div>
                        <div>

                            <?php
                            if (!empty($model2)) {
                                echo '
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Judul Buku</th>
                                <th scope="col">Nomor Eksemplar</th>
                                <th scope="col">Tanggal Kembali</th>
                                <th scope="col">Status</th>
                                
                            
                        </thead>
                        <tbody>';
                                $i = 1;
                                foreach ($model2 as $data) {

                                    echo '</tr>';
                                    echo '<th scope="col">' . $i . '</th>';
                                    echo '<th scope="col">' . $data->getJudul($data->document_id) . '</th>';
                                    echo '<th scope="col">' . $data->item_code . '</th>';
                                    echo '<th scope="col">' . $data->tanggal_pinjam . '</th>';
                                    echo '<th scope="col">' . $data->status_peminjaman . '</th>';

                                    echo '</tr>';
                                    $i++;
                                }
                                echo '
                        </tbody>
                    </table>';
                            }
                            ?>
                        </div>
                        <div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nibh urna, euismod ut ornare non, volutpat vel tortor. Integer laoreet placerat suscipit. Sed sodales scelerisque commodo. Nam porta cursus lectus. Proin nunc erat, gravida a facilisis quis, ornare id lectus. Proin consectetur nibh quis urna gravida mollis.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
