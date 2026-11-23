<?php

namespace backend\controllers;

use Yii;
use backend\models\Member;
use backend\models\MemberSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\web\components\FileHelper;
use yii\web\Response;
use yii\helpers\Html;
use backend\models\DokumenJdih;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends Controller
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
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Member model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Member();

        if ($model->load(Yii::$app->request->post())) {
            $model->status = Member::STATUS_ACTIVE;
            $model->setPassword(Yii::$app->security->generateRandomString(8));
            $model->generateAuthKey();

            $image = UploadedFile::getInstance($model, 'member_image');
            if (!empty($image)) {
                $model->member_image = FileHelper::sanitizeFilename($image->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->member_image;
                $image->saveAs($path);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Member berhasil ditambahkan');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Data Member Gagal ditambahkan, periksa kembali ');
                return $this->render('create', ['model' => $model]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionPassword($id)
    {
        $model = $this->findModel($id);
        $newPassword = Yii::$app->security->generateRandomString();
        $model->setPassword($newPassword);
        $model->save(false);
        Yii::$app->session->setFlash(
            'success',
            'Password user berhasil direset.<br>Password baru: <b>' . Html::encode($newPassword) . '</b>'
            . '<br><small>Catat sekarang, password ini hanya ditampilkan sekali.</small>'
        );
        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Member model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_image = $model->member_image;
        $old_image_ktp = $model->member_ktp;

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'member_image');
            if (!empty($image)) {
                $model->member_image = FileHelper::sanitizeFilename($image->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->member_image;
                $image->saveAs($path);
            } else {
                $model->member_image = $old_image;
            }

            $image_ktp = UploadedFile::getInstance($model, 'member_ktp');
            if (!empty($image_ktp)) {
                $model->member_ktp = FileHelper::sanitizeFilename($image_ktp->name);
                $path_ktp = Yii::getAlias('@common') . '/dokumen/' . $model->member_ktp;
                $image_ktp->saveAs($path_ktp);
            } else {
                $model->member_ktp = $old_image_ktp;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Member berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Member model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('danger', 'Data Member berhasil dihapus');
            return $this->redirect(['index']);
        } catch (\yii\db\IntegrityException $e) {
            Yii::$app->session->setFlash('error', "Data Member Tidak Dapat Dihapus Karena Dipakai Modul Lain");
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionParent($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $institutionType = ($id == DokumenJdih::KEMENTERIAN_ID) ? 'Kementerian' : 'Lembaga';
        $institutions = \backend\models\peraturan\Institutions::find()->where(['jenis' => $institutionType])->all();
        $results = [];
        foreach ($institutions as $institution) {
            $results[] = ['id' => $institution->id, 'name' => $institution->nama];
        }
        return $results;
    }
}