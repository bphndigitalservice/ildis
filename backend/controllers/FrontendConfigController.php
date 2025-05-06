<?php

namespace backend\controllers;

use Yii;
use backend\models\FrontendConfig;
use backend\models\search\FrontendConfigSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BeritaController implements the CRUD actions for Berita model.
 */
class FrontendConfigController extends Controller
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
     * Lists all Berita models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FrontendConfigSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Berita model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = FrontendConfig::findOne($id);
        switch ($model->id) {
            case '1':
            case '3':
            case '12':
                return $this->render('view-gambar', [
                    'model' => $model,
                ]);
                break;

            default:
                return $this->render('view-detail', [
                    'model' => $model,
                ]);
                break;
        }
        // return $this->render('view', [
        //     'model' => $this->findModel($id),
        // ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Konfigurasi Frontend berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeskripsi($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Konfigurasi Frontend berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('deskripsi', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpload($id)
    {
        $model = $this->findModel($id);
        $old_isi_konfig = $model->isi_konfig;

        if ($model->load(Yii::$app->request->post())) {

            $image = UploadedFile::getInstance($model, 'isi_konfig');
            if (!empty($image)) {
                $model->isi_konfig =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $image->name));
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->isi_konfig;
                $image->saveAs($path);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Berita berhasil ditambahkan');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('upload', [
                'model' => $model,
            ]);
        }
    }



    protected function findModel($id)
    {
        if (($model = FrontendConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
