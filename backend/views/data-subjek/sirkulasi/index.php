<?php

use mdm\admin\components\Helper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Peminjaman';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php Pjax::begin(['enablePushState' => false]); ?>

<div class="box-body table-responsive no-padding">
    <div class="box box-success box-solid">
        <div class="box-header with-border">
            <b>Cari Member</b>
        </div>
        <div class="box-body">
            <?php echo $this->render('_search', ['model' => $searchModel]);
            ?>
        </div>
    </div>


    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <b>Data Member</b>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => 'Ditampilkan {begin} - {end} dari {totalCount} Data',
                //'filterModel' => $searchModel,
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [

                    [
                        'class' => 'yii\grid\SerialColumn',
                        'contentOptions' => ['style' => 'width: 50px;', 'class' => 'text-center'],
                        'header' => 'No',
                        'headerOptions' => ['class' => 'text-center'],
                    ],


                    'username',

                    'member_name',
		     'personal_id_number',	
                    'member_email',
                    'member_since_date',
                    'register_date',
                    'expire_date',




                    [
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                        'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                        'header' => 'Aksi',
                        'template' => Helper::filterActionColumn('{view}'),

                        'buttons' => [
                            'view' => function ($url, $model) {
                                return
                                    Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-pencil"></b>  Peminjaman</span> ', ['view', 'id' => $model->id], ['title' => 'Lihat']);
                            },
                            'update' => function ($id, $model) {
                                return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b>  Pengembalian</span>', ['update', 'id' => $model->id], ['title' => 'Ubah']);
                            },

                            'history' => function ($id, $model) {
                                return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-search-plus"></b>  History</span>', ['update', 'id' => $model->id], ['title' => 'Ubah']);
                            },
                        ],
                    ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>
        </div>