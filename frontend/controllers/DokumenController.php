<?php

namespace frontend\controllers;

use frontend\models\DocumentType;
use Yii;
use frontend\models\Dokumen;
use frontend\models\DokumenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DokumenController implements the CRUD actions for Dokumen model.
 */
class DokumenController extends Controller
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
     * Lists all Dokumen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DokumenSearch();
        /*
        $searchModel = new DokumenSearch(['id'=>\Yii::$app->user->identity->direktorat_id]);
        $dataProvider->query->andWhere(['id'=>[2,3,4]]);
        */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex2($id)
    {
        $searchModel = new DokumenSearch(['bentuk_peraturan' => $id]);
        //$dataProvider->query->andWhere(['id'=>[2,3,4]]);
        /*
        $searchModel = new DokumenSearch(['id'=>\Yii::$app->user->identity->direktorat_id]);
        $dataProvider->query->andWhere(['id'=>[2,3,4]]);
        */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPeraturan()
    {
        $searchModel = new DokumenSearch(['tipe_dokumen' => 1]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-peraturan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMonografi()
    {
        $searchModel = new DokumenSearch(['tipe_dokumen' => 2]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-monografi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionArtikel()
    {
        $searchModel = new DokumenSearch(['tipe_dokumen' => 3]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-artikel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPutusan()
    {
        $searchModel = new DokumenSearch(['tipe_dokumen' => 4]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-putusan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionUu()
    {
        $searchModel = new DokumenSearch(['jenis_peraturan' => 'UNDANG-UNDANG']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-uu', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBuku()
    {
        $searchModel = new DokumenSearch(['jenis_peraturan' => 'BUKU HUKUM']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-buku', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMajalah()
    {
        $searchModel = new DokumenSearch(['jenis_peraturan' => 'MAJALAH HUKUM']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-majalah', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMa()
    {
        $searchModel = new DokumenSearch(['jenis_peraturan' => 'PUTUSAN MAHKAMAH AGUNG']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-putusan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionInggris()
    {
        $searchModel = new DokumenSearch(['singkatan_jenis' => 'PERATURAN BERBAHASA INGGRIS']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-inggris', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
     public function actionBerlaku()
    {
        $searchModel = new DokumenSearch(['status' => 'Berlaku', 'tipe_dokumen' => 1]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-berlaku', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionTberlaku()
    {
        $searchModel = new DokumenSearch(['status' => 'Tidak Berlaku']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-tberlaku', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single Dokumen model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Dokumen::findOne($id);

        $title = $model->judul;
        $deskripsi = $model->judul;

        $jenisperaturan = DocumentType::find()->where(['singkatan' => $model->singkatan_jenis])->one();
        if (!empty($jenisperaturan)) {
            $keywords = [
                $jenisperaturan->singkatan . ' Nomor ' . $model->nomor_peraturan . ' Tahun ' . $model->tahun_terbit,
                $jenisperaturan->singkatan . ' No. ' . $model->nomor_peraturan . ' Tahun ' . $model->tahun_terbit,
                $jenisperaturan->singkatan . ' ' . $model->nomor_peraturan . ' ' . $model->tahun_terbit,
                $jenisperaturan->singkatan . ' ' . $model->nomor_peraturan . '/' . $model->tahun_terbit,
                $jenisperaturan->singkatan . '-' . $model->nomor_peraturan . '-' . $model->tahun_terbit,
                $jenisperaturan->singkatan . '-no-' . $model->nomor_peraturan . '-tahun-' . $model->tahun_terbit,
                // $model->jenisperaturan->nama_peraturan . ' Nomor ' . $model->nomor . ' Tahun ' . $model->tahun_terbit . ' Tentang ' . ucwords(strtolower($model->judul)),
                // ucwords(strtolower($model->tentang)),
            ];
        }

        switch ($model->tipe_dokumen) {
            case 1:
                return $this->render('view-peraturan', ['model' => $this->findModel($id), 'title' => $title, 'deskripsi' => $deskripsi, 'keywords' => $keywords]);
                break;

            case 2:
                return $this->render('view-monografi', ['model' => $this->findModel($id), 'title' => $title, 'deskripsi' => $deskripsi]);
                break;

            case 3:
                return $this->render('view-artikel', ['model' => $this->findModel($id), 'title' => $title, 'deskripsi' => $deskripsi]);
                break;

            case 4:
                return $this->render('view-putusan', ['model' => $this->findModel($id), 'title' => $title, 'deskripsi' => $deskripsi]);
                break;
        }
    }

    /**
     * Creates a new Dokumen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
    








    /**
     * Finds the Dokumen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dokumen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dokumen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionJenis($id)
    {

        //  $dokumen = \backend\models\JenisDokumenHukum::find()->where(['singkatan_peraturan'=>$id])->one(); 
        $rows = \frontend\models\DokumenHukum::find()->where(['parent_id' => $id])->all();
        //echo "<option> Pilih Jenis Dokumen </option>";
        echo "<option></option>";
        if (count($rows) > 0) {
            foreach ($rows as $branch) {
                echo "<option value'" . $branch->id . "'>" . $branch->name . "</option>";
            }
        }
    }

    public function actionDownload($id)
    {

        $path = Yii::getAlias('@common') . '/dokumen/' . $id;
        if (file_exists($path)) {

            // $model = Dokumen::find()
            //    ->where(['lampiran' => $id])
            //    ->one();

            //    $model->hit_download = $model->hit_download +1;
            //    $model->save(); 
            return Yii::$app->response->sendFile($path);
        } else {
            throw new NotFoundHttpException("Tidak dapat menemukan file {$id}, silahkan hubungi admin");
        }
    }
}
