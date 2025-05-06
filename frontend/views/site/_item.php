<?php
use yii\helpers\Html;
?>                      


<div class="job-ad-item">
    <div class="item-info">                             
        <div class="ad-info">
            <span><?= Html::a($model->nama_rancangan, ['rancangan/view', 'id' => $model->id],['class'=>'title']);?></span>
            <div class="ad-meta">
                <ul>
                    <li><a href="#"><i class="fa fa-money" aria-hidden="true"></i><?=$model->jenis_rancangan;?></a></li>
                    <li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><?=$model->program;?></a></li>
                    <li><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i><?=$model->tahun;?></a></li>
                    <li><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i>Berakhir pada : <?=$model->tanggal_akhir_publish;?></a></li>
                  
                </ul>
            </div><!-- ad-meta -->                                  
        </div><!-- ad-info -->
    </div>
    <div class="ad-info">
        <?= Html::a('Lihat Detail', ['rancangan/view', 'id' => $model->id],['class'=>'btn btn-primary']);?>
        <?= Html::a('Partisipasi', ['rancangan/view', 'id' => $model->id],['class'=>'btn btn-primary']);?>    
    </div>                       
</div>                                                              
                       