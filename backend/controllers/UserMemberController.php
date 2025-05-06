<?php

namespace backend\controllers;

use Yii;
use backend\models\UserMember;
use backend\models\Signup;
use mdm\admin\models\User;
use backend\models\UserMemberSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UserMemberController implements the CRUD actions for UserMember model.
 */
class UserMemberController extends Controller
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
     * Lists all UserMember models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserMemberSearch();
        /*
        $searchModel = new UserMemberSearch(['id'=>\Yii::$app->user->identity->direktorat_id]);
        $dataProvider->query->andWhere(['id'=>[2,3,4]]);
        */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserMember model.
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
     * Creates a new UserMember model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
    
    public function actionCreate()
    {
        $model = new UserMember();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
     */

    // public function actionCreate()
    // {
    //     $model = new UserMember();

    //     if ($model->load(Yii::$app->request->post())) {

    //         $model->setPassword('123456');
    //         //$model->password_hash = Yii::$app->security->generatePasswordHash($_POST['UserMember']['password_hash']);
    //         $model->generateAuthKey();
    //         /*
    //         isi parameter tambahan

    //         $model->id = md5(uniqid(mt_rand(), true));
    //         $jenis = $_POST['UserMember']['field']);    
    //         $model->tahun_ln =  date('Y', strtotime($_POST['Peraturan']['tgl_diundangkan']));
    //         */


    //         if ($model->save()) {
    //             Yii::$app->session->setFlash('success', 'Data UserMember berhasil ditambahkan');
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         } else {
    //             Yii::$app->session->setFlash('error', 'Data UserMember Gagal ditambahkan, periksa kembali ');
    //             return $this->render('create', ['model' => $model]);
    //         }
    //     } else {
    //         return $this->render('create', [
    //             'model' => $model,
    //         ]);
    //     }
    // }


    public function actionCreate()
    {
        $model = new Signup();
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($user = $model->signup()) {
                //return $this->goHome();
                return $this->redirect(['view', 'id' => $user->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data UserMember berhasil diubah');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('danger', 'Data UserMember berhasil dihapus');
            return $this->redirect(['index']);
        } catch (\yii\db\IntegrityException  $e) {
            Yii::$app->session->setFlash('error', "Data UserMember Tidak Dapat Dihapus Karena Dipakai Modul Lain");
            return $this->redirect(['index']);
        }
    }



    /**
     * Finds the UserMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserMember::findOne($id)) !== null) {
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
}
