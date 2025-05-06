<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Berita */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Beritas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="box-body table-responsive no-padding">
    <div class="box box-primary box-solid">
        <div class="box-header with-border">

            <b>Detail Data Berita</b>
        </div>

        <div class="box-body">

            <div class="box-header">
                <?= Html::a('<i class="fa fa-mail-reply"></i> Kembali', ['index'], ['class' => 'btn btn-success btn-flat']) ?>
                
                <p></p>
            </div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                   [
                    'attribute' => 'nama_konfig',
                    'format' => 'raw',
                    'label'=>'Jenis Konfigurasi',
                ],

                [
                    'attribute' => 'isi_konfig',
                    'format' => 'raw',
                    'label'=>'Konfigurasi',
                ],

            ],
        ]) ?>
    </div>
</div>
</div>