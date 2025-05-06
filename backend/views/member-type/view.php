<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MemberType */

$this->title = $model->member_type_name;
$this->params['breadcrumbs'][] = ['label' => 'Member Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="box-body table-responsive no-padding">
    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Detail Type Member</h3>
        </div>

        <div class="box-body">

            <div class="box-header">
                <?= Html::a('Update', ['update', 'id' => $model->id, 'member_type_name' => $model->member_type_name, 'loan_limit' => $model->loan_limit, 'loan_periode' => $model->loan_periode], ['class' => 'btn btn-primary btn-flat']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id, 'member_type_name' => $model->member_type_name, 'loan_limit' => $model->loan_limit, 'loan_periode' => $model->loan_periode], [
                    'class' => 'btn btn-danger btn-flat',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
                <p></p>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [

                        'member_type_name',
                        'loan_limit',
                        'loan_periode',


                        'fine_each_day',

                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>

