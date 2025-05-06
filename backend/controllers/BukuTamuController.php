<?php

namespace backend\controllers;

use Yii;
use backend\models\BukuTamu;
use backend\models\search\BukuTamuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BukuTamuController implements the CRUD actions for BukuTamu model.
 */
class BukuTamuController extends Controller
{
    /**
     * @inheritDoc
     */
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
     * Lists all BukuTamu models.
     *
     * @return string
     */

    public function actionAdmin()
    {
        $searchModel = new BukuTamuSearch();
        /*
         $searchModel = new DaerahSearch(['id'=>\Yii::$app->user->identity->direktorat_id]);
         $dataProvider->query->andWhere(['id'=>[2,3,4]]);
         */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex()
    {
        $this->layout = 'main-buku-tamu';
        $model = new BukuTamu();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->tanggal_masuk = date('Y-m-d');
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Terima kasih atas kunjungan anda');
                    return $this->redirect(['index']);
                }
            } else {
                $model->loadDefaultValues();
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $this->layout = 'main-buku-tamu';
        $model = new BukuTamu();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Terima kasih atas kunjungan anda');
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


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


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = BukuTamu::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
