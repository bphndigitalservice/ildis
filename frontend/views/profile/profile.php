<?php
/* @var $this yii\web\View */


use yii\helpers\Html;
use common\widgets\Alert;

use yii\widgets\DetailView;
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

<?= Alert::widget() ?>
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
                <h4 id="alert">Profile</h4>

                <div class="horizontaltab tab-style4">
                    <ul class="resp-tabs-list hor_1">
                        <li> Data Login</li>
                        <li> Data Member</li>

                    </ul>
                    <div class="resp-tabs-container hor_1">
                        <div>
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'username',
                                    //'password_hash',
                                    [
                                        'label'=>'Password',
                                        'value'=>'******************',
                                    ],

                                    
                                    [

                                        'label' =>'Tipe Keanggotaaan',
                                        'value'=>function($data){
                                            return $data->member->member_type_name;
                                        }
                                    ],
                                    
                                    [
                                        'attribute'=>'member_since_date',
                                        'value' =>$model->getTanggal($model->member_since_date), 
                                    ],

                                     [
                                        'attribute'=>'register_date',
                                        'value' =>$model->getTanggal($model->register_date), 
                                    ],
                                    [
                                        'attribute'=>'expire_date',
                                        'value' =>$model->getTanggal($model->expire_date), 
                                    ],
                                    
                                ],
                            ]) ?>


                            <?= Html::a('Ubah Password', ['password', 'id' => $model->id], ['class' => 'butn']) ?>
                        </div>
                        <div>
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'member_name',
                                    //'birth_date',
                                     [
                                        'attribute'=>'birth_date',
                                        'value' =>$model->getTanggal($model->birth_date), 
                                    ],
                                    'personal_id_number',
                                    'gender',
                                    'member_address:ntext',
                                    'postal_code',
                                    'member_email:email',
                                    'inst_name',
                                    'phone_number',
                                    'fax_number',
                                    [
                                        'label' => 'Photo Member',
                                        'format' => 'raw',
                                        'value' => function ($data) {
                                            return Html::img(\Yii::getAlias('@imageurl') . '/common/dokumen/' . $data->member_image, ['alt' => 'myImage', 'width' => '300', 'height' => 'auto']);
                                        },
                                    ],
                                ],
                            ]) ?>
                            <?= Html::a('Ubah Data', ['update', 'id' => $model->id], ['class' => 'butn']) ?>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
</section>
