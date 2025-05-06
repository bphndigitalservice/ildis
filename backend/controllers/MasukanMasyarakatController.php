<?php

namespace backend\controllers;

use Yii;
use backend\models\MasukanMasyarakat;
use backend\models\search\MasukanMasyarakatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MasukanMasyarakatController implements the CRUD actions for MasukanMasyarakat model.
 */
class MasukanMasyarakatController extends Controller
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
     * Lists all MasukanMasyarakat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasukanMasyarakatSearch();
        /*
        $searchModel = new MasukanMasyarakatSearch(['id'=>\Yii::$app->user->identity->direktorat_id]);
        $dataProvider->query->andWhere(['id'=>[2,3,4]]);
        */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasukanMasyarakat model.
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
     * Creates a new MasukanMasyarakat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
    
    public function actionCreate()
    {
        $model = new MasukanMasyarakat();

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
        $model = new MasukanMasyarakat();

        if ($model->load(Yii::$app->request->post()))
        {
            /*
            isi parameter tambahan
            
            $model->id = md5(uniqid(mt_rand(), true));
            $jenis = $_POST['MasukanMasyarakat']['field']);    
            $model->tahun_ln =  date('Y', strtotime($_POST['Peraturan']['tgl_diundangkan']));
            */
        

            if ($model->save()) 
            {
                Yii::$app->session->setFlash('success', 'Data MasukanMasyarakat berhasil ditambahkan');
                return $this->redirect(['view', 'id' => $model->id]);
            } else 
            {
                Yii::$app->session->setFlash('error', 'Data MasukanMasyarakat Gagal ditambahkan, periksa kembali ');
                return $this->render('create', ['model' => $model]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionDownload($id) 
    { 

        $path = Yii::getAlias('@common'). '/uploads/masyarakat/' . $id;
        if (file_exists($path)) {

            return Yii::$app->response->sendFile($path);
        } else {
            throw new NotFoundHttpException("can't find {$id} file");
        }
    } 
    /**
     * Updates an existing MasukanMasyarakat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data MasukanMasyarakat berhasil diubah');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionPublish($id)
    {
        $model = $this->findModel($id);
        $model->is_publish = 1;
        $model->save(false);
        
        Yii::$app->session->setFlash('success', 'Komentar Masyarakat berhasil dipublish');
        return $this->redirect(['/perencanaan/view', 'id' => $model->rancangan_id]);
    }    

    public function actionUnpublish($id)
    {
        $model = $this->findModel($id);
        $model->is_publish = 0;
        $model->save(false);
        
        Yii::$app->session->setFlash('warning', 'Komentar Masyarakat berhasil dinonaktifkan');
        return $this->redirect(['/perencanaan/view', 'id' => $model->rancangan_id]);
    }      

    /**
     * Deletes an existing MasukanMasyarakat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try
        {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('danger', 'Data MasukanMasyarakat berhasil dihapus');
            return $this->redirect(['index']);
        }
        catch(\yii\db\IntegrityException  $e)
        {
            Yii::$app->session->setFlash('error', "Data MasukanMasyarakat Tidak Dapat Dihapus Karena Dipakai Modul Lain");
            return $this->redirect(['index']);
        } 
        
        
    }



    /**
     * Finds the MasukanMasyarakat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MasukanMasyarakat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MasukanMasyarakat::findOne($id)) !== null) {
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
