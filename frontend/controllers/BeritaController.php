<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Berita;
use frontend\models\search\BeritaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class BeritaController extends Controller
{
    const KEMENTERIAN_ID = '11e449f371bb47e09607313231373436';

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

    public function actionIndex()
    {
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
            'model2' => $searchModel
        ]);
    }

    public function actionCreate()
    {
        $model = new Berita();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Berita berhasil ditambahkan');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Data Berita Gagal ditambahkan, periksa kembali ');
                return $this->render('create', ['model' => $model]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data Berita berhasil diubah');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('danger', 'Data Berita berhasil dihapus');
            return $this->redirect(['index']);
        } catch (\yii\db\IntegrityException  $e) {
            Yii::$app->session->setFlash('error', "Data Berita Tidak Dapat Dihapus Karena Dipakai Modul Lain");
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id)
    {
        if (($model = Berita::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionParent($id)
    {
        $isKementerian = ($id == self::KEMENTERIAN_ID);
        $instansi = $isKementerian ? 'Kementerian' : 'Lembaga';
        $optionText = $isKementerian ? 'Pilih Kementerian' : 'Pilih Lembaga Non Kementerian';
        $institutions = $this->getInstitutionsByType($instansi);

        echo "<option>{$optionText}</option>";

        if (count($institutions) === 0) {
            echo "<option>Nenhum municipio cadastrado</option>";
            return;
        }

        foreach ($institutions as $institution) {
            echo "<option value='{$institution->id}'>{$institution->nama}</option>";
        }
    }

    /**
     * Ambil institutions berdasarkan jenis.
     * @param string $jenis
     * @return array
     */
    private function getInstitutionsByType($jenis)
    {
        return \backend\models\peraturan\Institutions::find()->where(['jenis' => $jenis])->all();
    }
}