<?php

namespace backend\controllers;

use backend\models\PcounterUsers;
use backend\models\search\PcounterUsersSeacrh;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CounterController implements the CRUD actions for PcounterUsers model.
 */
class CounterController extends Controller
{
    /**
     * @inheritDoc
     */
    public $tableUsers = 'pcounter_users';
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all PcounterUsers models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PcounterUsersSeacrh();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PcounterUsers model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PcounterUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PcounterUsers();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PcounterUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PcounterUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PcounterUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PcounterUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PcounterUsers::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionReport()
    {
        if(empty($_POST['tgls']))
        {
            $tgl1 = date('Y-m-01').' 00:00:00';
            $tgl2 = date('Y-m-t').' 23:59:59';
        }
        else
        {
            $e = explode(' - ', $_POST['tgls']);
            $z = explode('-', $e[0]);
            $tgl1 = $z[2].'-'.$z[1].'-'.$z[0].' 00:00:00';
            $w = explode('-', $e[1]);
            $tgl2 = $w[2].'-'.$w[1].'-'.$w[0].' 23:59:59';
        }

        $judul = "Laporan rekap pengunjung sejak ".date('d-m-Y', strtotime($tgl1))." s/d ".date('d-m-Y', strtotime($tgl2));

        $model = PcounterUsers::find()
        ->select([
            'DATE(creation_date) as tgl',
            'count(*) as jml'
        ])
        ->where(['>=', 'DATE(creation_date)', $tgl1])
        ->andWhere(['<=', 'DATE(creation_date)', $tgl2])
        ->groupBy(['DATE(creation_date)'])
        ->asArray()
        ->all();

       return $this->render('report', [
            'model' => $model,
            'judul' => $judul
        ]);
    }
    public function actionBarcode()
    {
        return $this->render('barcode', [
            
        ]);
    }
}
