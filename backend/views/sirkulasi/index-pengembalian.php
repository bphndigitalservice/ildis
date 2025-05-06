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

$this->title = 'Pengembalian Kilat';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php Pjax::begin(['enablePushState' => false]); ?>

<div class="box-body table-responsive no-padding">
    <div class="box box-success box-solid">
        <div class="box-header with-border">
            <b>Pencarian</b>
        </div>
        <div class="box-body">
            <?php echo $this->render('_search2', ['model' => $searchModel]);
            ?>
        </div>
    </div>

</div>
<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <b>Data Buku</b>
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

                'member',
                //'title',
                [
                    'label' =>'Judul Buku',
                    'attribute'=>'title',
                ],
                [
                    'label'=>'Kode Eksemplar',
                    'attribute'=>'item_code',
                ],
               // 'item_code',
                'tanggal_pinjam',

                [
                    'label' => 'Tanggal Harus Kembali',
                    'attribute' => 'tanggal_kembali',
                ],
                //'denda',
                //'member_id',
                [
                    'label'=>'Lama Keterlambatan',

                    'value'=>function($data){
                        return $data->getTerlambat($data->tanggal_pinjam,$data->tanggal_kembali);
                    }
                ],
                [
                    'label' => 'Denda',
                    'headerOptions' => ['style' => 'width: 200px;', 'class' => 'text-center'],
                    'value' => function ($data) {
                        return $data->getDenda($data->tanggal_kembali,$data->member_id);
                    }

                ],


                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width: 200px;', 'class' => 'text-center'],
                    'contentOptions' => ['style' => 'width: 200px;', 'class' => 'text-center'],
                    'header' => 'Aksi',
                    'template' => Helper::filterActionColumn('{kembali}&nbsp;&nbsp;{perpanjang}'),

                    'buttons' => [
                        'view' => function ($url, $model) {
                            return
                            Html::a('<span class="btn btn-sm btn-success"><b class="fa fa-search-plus"></b></span>', ['view', 'id' => $model->id], ['title' => 'Lihat']);
                        },
                        'update' => function ($id, $model) {
                            return Html::a('<span class="btn btn-sm btn-warning"><b class="fa fa-pencil"></b></span>', ['update', 'id' => $model->id], ['title' => 'Ubah']);
                        },

                        'kembali' => function ($url, $model) {
                            $url = Url::to(['kembali', 'id' => $model->id,'id2'=>$model->member_id]);
                            return Html::a('<span class="fa fa-pencil-square-o"> Kembali', $url, [
                                'title'        => 'kembali',
                                'class' => 'btn btn-primary btn-sm',
                                'data-confirm' => Yii::t('yii', 'Yakin mengembalikan buku ini ?'),
                                'data-method'  => 'post',
                            ]);
                        },

                        'perpanjang' => function ($url, $model) {
                            $url = Url::to(['perpanjang', 'id' => $model->id]);
                            return Html::a('<span class="fa fa-pencil-square-o"> Perpanjang', $url, [
                                'title'        => 'perpanjang',
                                'class' => 'btn btn-success btn-sm',
                                'data-confirm' => Yii::t('yii', 'Yakin memperpanjang buku ini ?'),
                                'data-method'  => 'post',
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="btn btn-sm btn-danger"><b class="fa fa-trash"></b></span>', ['delete', 'id' => $model->id], ['title' => 'Hapus', 'class' => '', 'data' => ['confirm' => 'Yakin akan menghapus data ini.', 'method' => 'post', 'data-pjax' => false],]);
                        },
                    ],
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>
    </div>