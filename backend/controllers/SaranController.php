<?php

namespace backend\controllers;

use Yii;
use backend\models\Saran;
use backend\models\SaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SaranController implements the CRUD actions for Saran model.
 */
class SaranController extends Controller
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
     * Lists all Saran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SaranSearch();
        /*
        $searchModel = new SaranSearch(['id'=>\Yii::$app->user->identity->direktorat_id]);
        $dataProvider->query->andWhere(['id'=>[2,3,4]]);
        */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Saran model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Saran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
    
    public function actionCreate()
    {
        $model = new Saran();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
     */

    public function actionCreate()
    {
        $model = new Saran();

        if ($model->load(Yii::$app->request->post()))
        {
            /*
            isi parameter tambahan
            
            $model->id = md5(uniqid(mt_rand(), true));
            $jenis = $_POST['Saran']['field']);    
            $model->tahun_ln =  date('Y', strtotime($_POST['Peraturan']['tgl_diundangkan']));
            */
        

            if ($model->save()) 
            {
                Yii::$app->session->setFlash('success', 'Data Saran berhasil ditambahkan');
                return $this->redirect(['view', 'id' => $model->id]);
            } else 
            {
                Yii::$app->session->setFlash('error', 'Data Saran Gagal ditambahkan, periksa kembali ');
                return $this->render('create', ['model' => $model]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing Saran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data Saran berhasil diubah');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Saran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try
        {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('danger', 'Data Saran berhasil dihapus');
            return $this->redirect(['index']);
        }
        catch(\yii\db\IntegrityException  $e)
        {
            Yii::$app->session->setFlash('error', "Data Saran Tidak Dapat Dihapus Karena Dipakai Modul Lain");
            return $this->redirect(['index']);
        } 
        
        
    }



    /**
     * Finds the Saran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Saran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Saran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionParent($id){
        if ($id== '11e449f371bb47e09607313231373436')
        {
            $instansi='Kementerian';
            $rows = \backend\models\peraturan\Institutions::find()->where(['jenis' => $instansi])->all();
            echo "<option>Pilih Kementerian</option>";
        }else
        {
            $instansi='Lembaga';
            $rows = \backend\models\peraturan\Institutions::find()->where(['jenis' => $instansi])->all();
            echo "<option>Pilih Lembaga Non Kementerian</option>";
        }

       // echo "<option>Pilih Kementerian/Lembaga</option>";
        
        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id'>$row->nama</option>";
            }
        }
        else{
            echo "<option>Nenhum municipio cadastrado</option>";
        }
        
    }
}
