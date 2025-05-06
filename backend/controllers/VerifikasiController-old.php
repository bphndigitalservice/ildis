<?php

namespace backend\controllers;

use Yii;
use backend\models\DokumenJdih;
use backend\models\search\VerifkasiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * VerifikasiController implements the CRUD actions for DokumenJdih model.
 */
class VerifikasiController extends Controller
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
     * Lists all DokumenJdih models.
     * @return mixed
     */
    public function actionIndex()
    {
         $searchModel = new VerifkasiSearch();
         /*
         $searchModel = new VerifkasiSearch(['id'=>\Yii::$app->user->identity->direktorat_id]);
         $dataProvider->query->andWhere(['id'=>[2,3,4]]);
         */
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

         return $this->render('index', [
             'searchModel' => $searchModel,
             'dataProvider' => $dataProvider,
         ]);

       
    }

    /**
     * Displays a single DokumenJdih model.
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
     * Creates a new DokumenJdih model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
    
    public function actionCreate()
    {
        $model = new DokumenJdih();

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
        $model = new DokumenJdih();

        if ($model->load(Yii::$app->request->post()))
        {
            /*
            isi parameter tambahan
            
            $model->id = md5(uniqid(mt_rand(), true));
            $jenis = $_POST['DokumenJdih']['field']);    
            $model->tahun_ln =  date('Y', strtotime($_POST['Peraturan']['tgl_diundangkan']));
            */
        

            if ($model->save()) 
            {
                Yii::$app->session->setFlash('success', 'Data berhasil ditambahkan');
                return $this->redirect(['view', 'id' => $model->id]);
            } else 
            {
                Yii::$app->session->setFlash('error', 'Data Gagal ditambahkan, periksa kembali ');
                return $this->render('create', ['model' => $model]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing DokumenJdih model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil diubah');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DokumenJdih model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try
        {
            $this->findModel($id)->delete();

        }
        catch(\yii\db\IntegrityException  $e)
        {
            Yii::$app->session->setFlash('error', "Data Tidak Dapat Dihapus Karena Dipakai Modul Lain");
        } 
        Yii::$app->session->setFlash('danger', 'Data berhasil hapus');
        return $this->redirect(['index']);
    }


    public function actionInactive($id = null)
    {
        if ($id != null){
            $model = $this->findModel($id);
            $model->is_publish = 0;
            if ($model->save()){
                Yii::$app->session->setFlash('danger','Produk Hukum tidak  diverifikasi');
                return $this->redirect(['index']);
            }else{
                print_r($model->getErrors());
            }           
        }else{
            $model = $this->findModel(\Yii::$app->user->identity->id);
            $model->is_publish = 0;
            $model->save();
            if ($model->save()){
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('success','Account inactive');
                return $this->goHome();
            }
        }
       
    }

    /**
     * Change is_publish user to Active
     * @param integer $id
     * @return mixed
     */
    public function actionActive($id)
    {
        $model = $this->findModel($id);
        $model->is_publish = 1;
        $model->save();
        Yii::$app->session->setFlash('success','Produk Hukum telah diverifikasi');
        return $this->redirect(['index']);
    }

    /**
     * Finds the DokumenJdih model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DokumenJdih the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DokumenJdih::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
