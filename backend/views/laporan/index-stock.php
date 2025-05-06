<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\DokumenJdih;
use backend\models\Eksemplar;
/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Laporan Stockopname';
$this->params['breadcrumbs'][] = ['label' => 'Laporan', 'url' => ['stockopname']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Detail Laporan Stockopname</h3>

    </div>
    <!-- /.box-header -->
    <div class="box-body">

        <table class="table table-bordered table-striped ">
            <tbody>

                <tr>
                    <td>Jumlah Koleksi Monografi</td>
                    <td><?= DokumenJdih::find()->where(['tipe_dokumen'=>1])->count()?></td>

                </tr>

                <tr>
                    <td>Jumlah Eksemplar</td>
                    <td><?= Eksemplar::find()->count()?></td>

                </tr>

                <tr>
                    <td>Jumlah Dipimjam</td>
                    <td><?= Eksemplar::find()->where(['status_eksemplar'=>'Dipinjam'])->count()?></td>

                </tr>

                <tr>
                    <td>Jumlah Tersedia</td>
                    <td><?= Eksemplar::find()->where(['status_eksemplar'=>'Tersedia'])->count()?></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
