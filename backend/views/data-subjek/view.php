<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DataSubyek */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Data Subyeks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="box-body table-responsive no-padding">
    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            
            <b>Detail Data Data Subyek</b>
        </div>
          
        <div class="box-body">

            <div class="box-header">
                <?= Html::a('<i class="fa fa-mail-reply"></i> Kembali', ['index'], ['class' => 'btn btn-success btn-flat']) ?>
                <?= Html::a('<i class="fa fa-pencil"></i> Ubah', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
                <?= Html::a('<i class="fa fa-trash"></i> Hapus', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-flat',
                    'data' => [
                        'confirm' => 'Yakin menghapus data ini?',
                        'method' => 'post',
                    ],
                ]) ?>
                <p></p>
            </div>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                'id_dokumen',
                'subyek',
                'tipe_subyek',
                'jenis_subyek',
                'status',
                'integrasi',
                '_created_by',
                '_updated_by',
                'created_at:datetime',
                'updated_at:datetime',
                    ],
                ]) ?>
        </div>
    </div>
</div>


