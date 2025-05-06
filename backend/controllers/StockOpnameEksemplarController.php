<?php

namespace backend\controllers;

use backend\models\Eksemplar;
use Yii;
use backend\models\StockOpnameEksemplar;
use backend\models\search\StockOpnameEksemplarSearch;
use backend\models\StockOpnameMonografi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * StockOpnameEksemplarController implements the CRUD actions for StockOpnameEksemplar model.
 */
class StockOpnameEksemplarController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new StockOpnameEksemplarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider2 = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider2->query->andWhere(['tahun' => date('Y')]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
        ]);
    }


    /**
     * Displays a single StockOpnameEksemplar model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "StockOpnameEksemplar #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-secondary float-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new StockOpnameEksemplar model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StockOpnameEksemplar();

        if ($model->load(Yii::$app->request->post())) {

            $periksa = StockOpnameEksemplar::find()->where(['kode_eksemplar' => $model->kode_eksemplar, 'tahun' => date('Y')])->one();
            if (!empty($periksa)) {

                Yii::$app->session->setFlash('danger', 'Data Eksemplar sudah diinput');
                return $this->redirect(['index']);
            }

            $eksemplar = Eksemplar::find()->where(['kode_eksemplar' => $model->kode_eksemplar])->one();
            if (!empty($eksemplar)) {
                $model->dokumen_id =  $eksemplar->id_dokumen;
            }


            $model->tahun = date('Y');
            $model->tanggal = date('Y-m-d');

            if ($model->save()) {

                $cek = StockOpnameMonografi::find()->where(['id_dokumen' => $model->dokumen_id, 'tahun' => date('Y')])->one();
                if (empty($cek)) {
                    $jumlah = Eksemplar::find()->where(['id_dokumen' => $model->dokumen_id])->count();
                    $monografi = new StockOpnameMonografi();
                    $monografi->id_dokumen = $model->dokumen_id;
                    $monografi->tahun = $model->tahun;
                    $monografi->jumlah_eksemplar = $jumlah;
                    $monografi->jumlah_scan = 1;
                    $monografi->save();
                } else {

                    $cek2 = StockOpnameMonografi::find()->where(['id_dokumen' => $model->dokumen_id, 'tahun' => date('Y')])->one();
                    if (!empty($cek2)) {
                        $cek2->jumlah_scan = StockOpnameEksemplar::find()->where(['dokumen_id' => $model->dokumen_id, 'tahun' => date('Y')])->count();

                        $cek2->save(false);
                    }
                }
                Yii::$app->session->setFlash('success', 'Data Eksemplar berhasil ditambahkan');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Kode Eksemplar belum tersedia, silahkan input kode eksemplar ');
                return $this->render('create', ['model' => $model]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionDelete($id)
    {
        try {
            $model = StockOpnameEksemplar::findOne($id);
            $model->delete();

            Yii::$app->session->setFlash('danger', 'Data StockOpname Eksemplar berhasil dihapus');

            $cek2 = StockOpnameMonografi::find()->where(['id_dokumen' => $model->dokumen_id, 'tahun' => date('Y')])->one();
            if (!empty($cek2)) {
                $cek2->jumlah_scan = StockOpnameEksemplar::find()->where(['dokumen_id' => $model->dokumen_id, 'tahun' => date('Y')])->count();
                $cek2->save(false);
            }
            return $this->redirect(['index']);
        } catch (\yii\db\IntegrityException  $e) {
            Yii::$app->session->setFlash('error', "Data Penerbit Tidak Dapat Dihapus Karena Dipakai Modul Lain");
            return $this->redirect(['index']);
        }
    }


    public function actionBulkdelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id)
    {
        if (($model = StockOpnameEksemplar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
