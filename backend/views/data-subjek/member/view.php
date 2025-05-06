<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Member */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="box-body table-responsive no-padding">
    <div class="box box-primary box-solid">
        <div class="box-header with-border">

            <b>Detail Data Member</b>
        </div>

        <div class="box-body">

            <div class="box-header">
                <?= Html::a('<i class="fa fa-mail-reply"></i> Kembali', ['index'], ['class' => 'btn btn-success btn-flat']) ?>
                <?= Html::a('<i class="fa fa-pencil"></i> Ubah', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>

                <p></p>
            </div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                    'username',

                    'status',
                    'member_name',
                    'gender',
                    'birth_date',
                    'member_type_id',
                    'member_address:ntext',
                    'member_email:email',
                    'postal_code',
                    'personal_id_number',
                    'inst_name',
                    
                    'member_since_date',
                    'register_date',
                    'expire_date',
                    'phone_number',
                    'fax_number',
                      'member_notes:ntext',
                                        [
                        'label' => 'Photo Member',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Html::img(\Yii::getAlias('@imageurl') . '/common/dokumen/' . $data->member_image, ['alt' => 'myImage', 'width' => '300', 'height' => 'auto']);
                        },
                    ],
                  
 
                ],
            ]) ?>
        </div>
    </div>
</div>