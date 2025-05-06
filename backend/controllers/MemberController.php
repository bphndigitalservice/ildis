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
        /*
        $searchModel = new MemberSearch(['id'=>\Yii::$app->user->identity->direktorat_id]);
        $dataProvider->query->andWhere(['id'=>[2,3,4]]);
        */
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

    /**
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
    
    public function actionCreate()
    {
        $model = new Member();

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
        $model = new Member();

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 10;
            $model->setPassword('123456');
            $model->generateAuthKey();

            $image = UploadedFile::getInstance($model, 'member_image');
            if (!empty($image)) {
                $model->member_image =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $image->name));
                $model->member_image = FileHelper::sanitizeFilename($image->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->member_image;
                $image->saveAs($path);
            }
            /*
            isi parameter tambahan
            
            $model->id = md5(uniqid(mt_rand(), true));
            $jenis = $_POST['Member']['field']);    
            $model->tahun_ln =  date('Y', strtotime($_POST['Peraturan']['tgl_diundangkan']));
            */


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
        $model->setPassword('123456');
        $model->save(false);
        Yii::$app->session->setFlash('success', 'User berhasil direset dengan password 123456');
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
                $model->member_image =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $image->name));
                $model->member_image = FileHelper::sanitizeFilename($image->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->member_image;
                $image->saveAs($path);
            }else{
                $model->member_image=$old_image;
            }

            $image_ktp = UploadedFile::getInstance($model, 'member_ktp');
            if (!empty($image_ktp)) {
                $model->member_ktp =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $image_ktp->name));
                $model->member_ktp = FileHelper::sanitizeFilename($member_ktp->name);
                $path_ktp = Yii::getAlias('@common') . '/dokumen/' . $model->member_ktp;
                $image_ktp->saveAs($path_ktp);
            }else{
                $model->member_ktp=$old_image_ktp;
            }

            if ($model->save()){


            Yii::$app->session->setFlash('success', 'Data Member berhasil diubah');
            return $this->redirect(['view', 'id' => $model->id]);
        } 
        }else {
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
        } catch (\yii\db\IntegrityException  $e) {
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
        if ($id == '11e449f371bb47e09607313231373436') {
            $instansi = 'Kementerian';
            $rows = \backend\models\peraturan\Institutions::find()->where(['jenis' => $instansi])->all();
            echo "<option>Pilih Kementerian</option>";
        } else {
            $instansi = 'Lembaga';
            $rows = \backend\models\peraturan\Institutions::find()->where(['jenis' => $instansi])->all();
            echo "<option>Pilih Lembaga Non Kementerian</option>";
        }

        // echo "<option>Pilih Kementerian/Lembaga</option>";

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                echo "<option value='$row->id'>$row->nama</option>";
            }
        } else {
            echo "<option>Nenhum municipio cadastrado</option>";
        }
    }

    public function actionShit()
    {
        echo "adada";
    }
}
