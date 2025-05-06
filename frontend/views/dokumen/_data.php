<?php

use yii\helpers\Html;
use frontend\models\DataLampiran;

$domain = yii\helpers\Url::base(true);

?>
<div class="border-bottom margin-40px-bottom padding-40px-bottom xs-padding-20px-bottom">
    <div class="card card-list border-0">
        <div class="row align-items-center">
            <div class="card-body no-padding-tb">
                <div class="d-flex justify-content-between align-items-center">
                    <p>

                        <?= Html::a($model->bentuk_peraturan, ['/dokumen/index2', 'id' => $model->bentuk_peraturan], ['class' => 'text-extra-dark-gray']); ?>
                    </p>
                    <ul>
                        <li><a href="#"><i class="margin-5px-right"></i><?= $model->tahun_terbit; ?></a></li>
                    </ul>
                </div>

                <p><?=
                        Html::a($model->judul, ['/dokumen/view', 'id' => $model->id], ['class' => 'text-primary', 'title' => 'lihat detail']);
                    ?></p>
                <div class="d-flex left-content-between align-items-left">

                    <?php
                    $lampiran = DataLampiran::find()->where(['id_dokumen' => $model->id])->one();

                    if (!empty($lampiran)) {
                        //echo $lampiran->getDokumen($lampiran->dokumen_lampiran);

                        echo  Html::a('Dokumen', ['/common/dokumen/' . $lampiran->dokumen_lampiran], ['class' => 'text-theme-color mr-3', 'target' => '_blank', 'title' => 'lihat file']);

                        //echo Html::a('Dokumen', ['/dokumen/download', 'id' => $lampiran->dokumen_lampiran], ['target' => '_blank', 'class' => 'text-theme-color mr-3', 'title' => 'download file']);
                    }

                    if (!empty($model->abstrak)) {
                        echo '<br>';
                        //echo Html::a('Abstrak', ['/dokumen/download', 'id' => $model->abstrak], ['class' => 'text-theme-color', 'title' => 'download file']);
                        echo  Html::a('Abstrak', ['/common/dokumen/' . $model->abstrak], ['class' => 'text-theme-color mr-3', 'target' => '_blank', 'title' => 'lihat file']);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>