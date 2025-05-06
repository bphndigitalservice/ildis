<?php
use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="box-header">
  
    <p></p>
    <?= GridView::widget([
        'dataProvider' => $log,
        'summary' => 'Ditampilkan {begin} - {end} dari {totalCount} Data',                
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 50px;', 'class' => 'text-center'],
                'header'=>'No',
                'headerOptions' => ['class' => 'text-center'],
            ],  
      

                            
            [
                'label' =>'keterangan',
                'value' =>'keterangan',
            ], 
            
               
        ],
    ]); ?>            
</div>