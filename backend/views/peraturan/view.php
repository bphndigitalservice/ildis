<?php

use yii\helpers\Html;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model backend\models\Peraturan */

$this->title = 'Detail Dokumen';
$this->params['breadcrumbs'][] = ['label' => 'Peraturan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs dashboard_tabs_cl">
        <li class="active"><a href="#tab_1" data-toggle="tab">Data Utama</a></li>
        <li><a href="#tab_2" data-toggle="tab">T.E.U</a></li>
        <li><a href="#tab_3" data-toggle="tab">Subjek</a></li>

        <li><a href="#tab_5" data-toggle="tab">Peraturan Terkait</a></li>
        <li><a href="#tab_6" data-toggle="tab">Dokumen Terkait</a></li>
        <li><a href="#tab_7" data-toggle="tab">Hasil Uji Materi</a></li>
        <li><a href="#tab_8" data-toggle="tab">Status</a></li>
        <li><a href="#tab_10" data-toggle="tab">Abstrak</a></li>
        <li><a href="#tab_9" data-toggle="tab">Log User</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <?= $this->render('_detail', ['model' => $model]) ?>
        </div>
        <div class="tab-pane" id="tab_2">
            <?= $this->render('teu/_teu', ['teu' => $teu, 'id' => $model->id]) ?>
        </div>
        <div class="tab-pane" id="tab_3">
            <?= $this->render('subyek/_subyek', ['subyek' => $subyek, 'id' => $model->id]) ?>
        </div>

        <div class="tab-pane" id="tab_5">
            <?= $this->render('peraturan-terkait/_peraturan', ['peraturan' => $peraturan, 'id' => $model->id]) ?>
        </div>
        <div class="tab-pane" id="tab_6">
            <?= $this->render('dokumen/_dokumen', ['dokumen' => $dokumen, 'id' => $model->id]) ?>
        </div>
        <div class="tab-pane" id="tab_7">
            <?= $this->render('hasil-uji-materi/_ujimateri', ['ujimateri' => $ujimateri, 'id' => $model->id]) ?>
        </div>
        <div class="tab-pane" id="tab_8">
            <?= $this->render('status/_status', ['status' => $status, 'id' => $model->id]) ?>
        </div>

        <div class="tab-pane" id="tab_10">
            <?= $this->render('abstrak/_abstrak', ['abstrak' => $abstrak, 'id' => $model->id]) ?>
        </div>

        <div class=" tab-pane" id="tab_9">
            <?= $this->render('log/_log', ['log' => $log]) ?>
        </div>
    </div>


    <?php $this->registerJs(
        '$("document").ready(function(){
    if (typeof(Storage) !== "undefined") {
                
        var dash_localVar = localStorage.getItem("dash_activ_tab"+getUrlPath());
        if(dash_localVar){

            $(".dashboard_tabs_cl > li").removeClass("active");
            $(".tab-content > div").removeClass("active");

            var hrefAttr = "a[href=\'"+dash_localVar+"\']";
            if( $(hrefAttr).parent() ){
                $(hrefAttr).parent().addClass("active");
                $(""+dash_localVar+"").addClass("active");
            }
                
        }

        $(".dashboard_tabs_cl a").click(function (e) {
            //alert(window.location.pathname);                  
            e.preventDefault();
            localStorage.setItem("dash_activ_tab"+getUrlPath(), $( this ).attr( "href" ));
        });
        function getUrlPath(){
            var returnVar = "_indexpg";
            var splitStr = window.location.href;
            var asdf = splitStr.split("?r=");
            if(asdf[1]){
                var furthrSplt = asdf[1].split("&");
                if(furthrSplt[0]){
                    returnVar = furthrSplt[0];
                }else{
                    returnVar = asdf[1];
                }
            }
            return returnVar;
        }
    }
    });'
    ); ?>