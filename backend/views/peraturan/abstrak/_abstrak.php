<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
?>

<?php
if (empty($abstrak)) {
    echo Html::a('<i class="fa fa-plus-circle"></i> Tambah Abstrak', ['tambah-abstrak', 'id' => $id], ['class' => 'btn btn-success btn-flat']);
} else {
    echo Html::a('<i class="fa fa-plus-circle"></i> Edit Abstrak ', ['edit-abstrak', 'id' => $abstrak->id], ['class' => 'btn btn-warning btn-flat']) . '&nbsp;' . Html::a('<i class="fa fa-plus-circle"></i> Hapus Abstrak ', ['hapus-abstrak', 'id' => $abstrak->id], ['class' => 'btn btn-danger btn-flat']) . '&nbsp;' .
        Html::a('<i class="fa fa-print"></i> Cetak ', ['cetak-abstrak', 'id' => $abstrak->id], ['class' => 'btn btn-primary btn-flat']);

    echo '<br><br>';


    echo substr($abstrak->subjek, 0, -1);
    echo '<br>';
    echo $abstrak->tahun;
    echo '<br>';
    echo $abstrak->singkatan;

    echo '<br>';
    echo $abstrak->judul;
    echo '<br><br>';

    echo '<table style="width:100%">';
    echo '<tr><td rowspan="4" style="width:10%" valign="top">&nbsp&nbspABSTRAK&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:</td></tr>';
    echo '<tr><td style="width:1%" valign="top">-</td><td style="width:95%; text-align:justify;">' . $abstrak->isi_abstrak_1 . '</td></tr>';
    echo '<tr><td valign="top">-</td><td style="width:95%; text-align:justify;">' . $abstrak->isi_abstrak_2 . '</td></tr>';
    echo '<tr><td valign="top">-</td><td style="width:95%; text-align:justify;">' . $abstrak->isi_abstrak_3 . '</td></tr>';

    echo '</table>';
    echo '<br>';
    echo '<table style="width:100%">';
    echo '<tr><td rowspan="6" style="width:1%" valign="top">CATATAN&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:</td></tr>';
    echo '<tr><td <td style="width:1%" valign="top">-</td><td style="width:80%; text-align:justify;">' . $abstrak->catatan_1 . '</td></tr>';
    if (!empty($abstrak->catatan_2)) {
        echo '<tr><td <td style="width:1%" valign="top">-</td><td style="width:70%; text-align:justify;">' . $abstrak->catatan_2 . '</td></tr>';
    }
    if (!empty($abstrak->catatan_3)) {
        echo '<tr><td <td style="width:1%" valign="top">-</td><td style="width:70%; text-align:justify;">' . $abstrak->catatan_3 . '</td></tr>';
    }
    if (!empty($abstrak->catatan_4)) {
        echo '<tr><td <td style="width:1%" valign="top">-</td><td style="width:70%; text-align:justify;">' . $abstrak->catatan_4 . '</td></tr>';
    }
    if (!empty($abstrak->catatan_5)) {
        echo '<tr><td <td style="width:1%" valign="top">-</td><td style="width:98%; text-align:justify;">' . $abstrak->catatan_5 . '</td></tr>';
    }
    echo '</table>';
}

