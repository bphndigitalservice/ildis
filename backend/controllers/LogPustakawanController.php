<?php

namespace backend\controllers;

use Yii;
use backend\models\LogPustakawan;
use backend\models\LogPustakawanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * LogPustakawanController implements the CRUD actions for LogPustakawan model.
 */
class LogPustakawanController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all LogPustakawan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogPustakawanSearch();
        /*
        $searchModel = new LogPustakawanSearch(['id'=>\Yii::$app->user->identity->direktorat_id]);
        $dataProvider->query->andWhere(['id'=>[2,3,4]]);
        */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LogPustakawan model.
     * @param integer $id
     * @return mixed
     */


    /**
     * Creates a new LogPustakawan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
    
    public function actionCreate()
    {
        $model = new LogPustakawan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
     */






    /**
     * Finds the LogPustakawan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LogPustakawan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LogPustakawan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
