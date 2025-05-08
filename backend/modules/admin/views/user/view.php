<?php

use yii\helpers\Html;
use kartik\grid\GridView;

// use yii\widgets\DetailView;
// use mdm\admin\components\Helper;
use yii\bootstrap\ActiveForm;
use backend\models\LogPustakawan;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$controllerId = $this->context->uniqueId . '/';

$count = LogPustakawan::find()->where(['created_by' => \Yii::$app->user->identity->id])->count();
?>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">

                        <?= Html::img(\Yii::getAlias('@imageurl') . '/common/dokumen/' . $model->picture, ['class' => 'profile-user-img img-responsive img-circle', 'alt' => 'myImage', 'width' => '160', 'height' => 'auto']); ?>
                        <h3 class="profile-username text-center"><?= $model->username ?></h3>
                        <p class="text-muted text-center"><?= $model->email ?></p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Hak Akses</b>
                                <a class="pull-right">
                                    <?php $group = Yii::$app->authManager->getAssignments($model->id);
                                    foreach ($group as $data) {
                                        echo '<span class="label label-warning label-md">' . $data->roleName . '</span>&nbsp;';
                                    }
                                    ?>
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Jumlah Aktifitas</b> <a class="pull-right"><?= $count; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Tanggal dibuat</b> <a
                                        class="pull-right"><?= Yii::$app->formatter->asDateTime($model->created_at, 'php:d/m/Y'); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="nav-tabs-custom">

                    <ul class="nav nav-tabs dashboard_tabs_cl">
                        <li class="active"><a href="#timeline" data-toggle="tab" aria-expanded="false">Aktifitas</a>
                        </li>
                        <li><a href="#settings" data-toggle="tab" aria-expanded="true">Ganti Password</a></li>
                        <li><a href="#password" data-toggle="tab" aria-expanded="true">Ganti Photo Profil</a></li>


                    </ul>
                    <div class="tab-content">
                        <!-- /.tab-pane -->
                        <div class="tab-pane active" id="timeline">

                            <?= GridView::widget([

                                'panel' => [
                                    'type' => GridView::TYPE_PRIMARY,
                                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Data Log User' . '</h3>',
                                ],
                                'toolbar' => [

                                    '{export}',
                                    '{toggleData}'
                                ],
                                'dataProvider' => $log,
                                'summary' => 'Ditampilkan {begin} - {end} dari {totalCount} Data',

                                'layout' => "{items}\n{summary}\n{pager}",
                                'columns' => [

                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                        'contentOptions' => ['style' => 'width: 50px;', 'class' => 'text-center'],
                                        'header' => 'No',
                                        'headerOptions' => ['class' => 'text-center'],
                                    ],

                                    //'id',
                                    [
                                        'label' => 'keterangan',
                                        'value' => 'keterangan',
                                    ],
                                    // 'created_by',
                                    // 'updated_by',
                                    // 'created_at',
                                    // 'updated_at',


                                ],
                            ]); ?>

                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="settings">
                            <?php $form = ActiveForm::begin(['id' => 'form-change']); ?>
                            <?= $form->field($changePassword, 'oldPassword')->passwordInput()->label('Password lama') ?>
                            <?= $form->field($changePassword, 'newPassword')->passwordInput()->label('Password baru') ?>
                            <?= $form->field($changePassword, 'retypePassword')->passwordInput()->label('Ulangi Password baru') ?>
                            <div class="form-group">
                                <?= Html::submitButton(Yii::t('rbac-admin', 'Ganti Password'), ['class' => 'btn btn-primary', 'name' => 'change-button']) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>

                        <div class="tab-pane" id="password">
                            <?php $form = ActiveForm::begin(['id' => 'form-change-picture']); ?>
                            <?=
                            $form->field($model, 'picture')->widget(FileInput::classname(), [
                                'pluginOptions' => ['allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'bmp'], 'showUpload' => false,],
                            ])
                            ?>


                            <div class="form-group">
                                <?= Html::submitButton(Yii::t('rbac-admin', 'Ganti Photo'), ['class' => 'btn btn-primary', 'name' => 'change-button2']) ?>
                            </div>
                            <?php ActiveForm::end(); ?>

                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>

<?php
// if ($model->status == 0 && Helper::checkRoute($controllerId . 'activate')) {
//     echo Html::a(Yii::t('rbac-admin', 'Activate'), ['activate', 'id' => $model->id], [
//         'class' => 'btn btn-primary',
//         'data' => [
//             'confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
//             'method' => 'post',
//         ],
//     ]);
// }
?>


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