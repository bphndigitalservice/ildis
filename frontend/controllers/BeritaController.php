<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Berita;
use frontend\models\search\BeritaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use frontend\models\Dokumen;

/**
 * Public berita listing and detail.
 *
 * Create / update / delete are intentionally not exposed on the frontend
 * (GHSA-prrm-3g6v-35vv). Content management lives in the backend app only.
 */
class BeritaController extends Controller
{
    /**
     * Reject legacy write routes that previously allowed unauthenticated / member CRUD.
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        throw new ForbiddenHttpException('Pengelolaan berita hanya tersedia melalui panel administrasi.');
    }

    /**
     * @param int|string $id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        throw new ForbiddenHttpException('Pengelolaan berita hanya tersedia melalui panel administrasi.');
    }

    /**
     * @param int|string $id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        throw new ForbiddenHttpException('Pengelolaan berita hanya tersedia melalui panel administrasi.');
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
        $model = $this->findModel($id);
        if ($model->status !== null && (int) $model->status === 0) {
            throw new NotFoundHttpException('Berita tidak ditemukan atau belum dipublikasikan.');
        }

        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'model2' => $searchModel
        ]);
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $institutionType = ($id == Dokumen::KEMENTERIAN_ID) ? 'Kementerian' : 'Lembaga';
        $institutions = \backend\models\peraturan\Institutions::find()->where(['jenis' => $institutionType])->all();
        $results = [];
        foreach ($institutions as $institution) {
            $results[] = ['id' => $institution->id, 'name' => $institution->nama];
        }
        return $results;
    }
}
