<?php

namespace backend\controllers;

use Yii;
use backend\models\Putusan;
use backend\models\Pengarang;
use backend\models\LogPustakawan;
use backend\models\DataPengarang;
use backend\models\JenisPeraturan;
use backend\models\DataSubyek;
use backend\models\DataStatus;
use backend\models\DataLampiran;
use backend\models\HasilUjiMateri;
use backend\models\DokumenTerkait;
use backend\models\PeraturanTerkait;
use backend\models\PutusanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\web\components\FileHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * PeraturanController implements the CRUD actions for Peraturan model.
 */
class PutusanController extends Controller
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
     * Lists all Peraturan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PutusanSearch();
        /*
        $searchModel = new PeraturanSearch(['id'=>\Yii::$app->user->identity->direktorat_id]);
        $dataProvider->query->andWhere(['id'=>[2,3,4]]);
        */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Peraturan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $teu = new ActiveDataProvider([
            'query' => DataPengarang::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10]
        ]);

        $subyek = new ActiveDataProvider([
            'query' => DataSubyek::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10]
        ]);

        $lampiran = new ActiveDataProvider([
            'query' => DataLampiran::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10]
        ]);

        $peraturan = new ActiveDataProvider([
            'query' => PeraturanTerkait::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10]
        ]);

        $dokumen = new ActiveDataProvider([
            'query' => DokumenTerkait::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10]
        ]);

        $ujimateri = new ActiveDataProvider([
            'query' => HasilUjiMateri::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10]
        ]);

        $status = new ActiveDataProvider([
            'query' => DataStatus::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10]
        ]);

        $log = new ActiveDataProvider([
            'query' => LogPustakawan::find()->where(['dokumen_id' => $id]),
            'pagination' => ['pageSize' => 10]
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'teu' => $teu,
            'subyek' => $subyek,
            'lampiran' => $lampiran,
            'peraturan' => $peraturan,
            'dokumen' => $dokumen,
            'status' => $status,
            'ujimateri' => $ujimateri,
            'log' => $log,

        ]);
    }

    /**
     * Creates a new Peraturan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
    
    public function actionCreate()
    {
        $model = new Peraturan();

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
        $model = new Putusan();
        $lampiran = new DataLampiran();
        $teu = new DataPengarang();
        $log = new LogPustakawan();

        //if ($model->load(Yii::$app->request->post()))
        if ($model->load(Yii::$app->request->post()) && $lampiran->load(Yii::$app->request->post())) {
            $abstrak = UploadedFile::getInstance($model, 'abstrak');
            if (!empty($abstrak)) {
                $model->abstrak =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $abstrak->name));
                $model->abstrak = FileHelper::sanitizeFilename($abstrak->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->abstrak;
                $abstrak->saveAs($path);
            }
            $model->save();

            $lampiran->id_dokumen = $model->id;
            $dokumen_lampiran = UploadedFile::getInstance($lampiran, 'dokumen_lampiran');

            if (!empty($dokumen_lampiran)) {
                $lampiran->dokumen_lampiran =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $dokumen_lampiran->name));
                $lampiran->dokumen_lampiran = FileHelper::sanitizeFilename($dokumen_lampiran->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $lampiran->dokumen_lampiran;
                $dokumen_lampiran->saveAs($path);
            }
            $lampiran->url_lampiran = Yii::getAlias('@imageurl') . '/common/dokumen/' . $lampiran->dokumen_lampiran;

            $lampiran->save();



            $log->dokumen_id = $model->id;
            $log->controller = 'Putusan';
            $log->aksi = 'Tambah Peraturan';
            $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data putusan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
            $log->save();

            Yii::$app->session->setFlash('success', 'Data Putusan berhasil ditambahkan');
            return $this->redirect(['view', 'id' => $model->id]);

            // $jenisperaturan = JenisPeraturan::findOne($model->jenis_peraturan);
            // if(!empty($jenisperaturan))
            // {
            //     $model->jenis_peraturan = $jenisperaturan->name;
            //    // $model->singkatan_jenis = 'UU';
            //     $model->bentuk_peraturan = $jenisperaturan->name;
            // }
            /*
            isi parameter tambahan
            
            $model->id = md5(uniqid(mt_rand(), true));
            $jenis = $_POST['Peraturan']['field']);    
            $model->tahun_ln =  date('Y', strtotime($_POST['Peraturan']['tgl_diundangkan']));
            */
        } else {
            return $this->render('create', [
                'model' => $model,
                'lampiran' => $lampiran,

            ]);
        }
    }


    /**
     * Updates an existing Peraturan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         Yii::$app->session->setFlash('success', 'Data Putusan berhasil diubah');
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     } else {
    //         return $this->render('update', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_abstrak = $model->abstrak;

        if ($model->load(Yii::$app->request->post())) {

            $abstrak = UploadedFile::getInstance($model, 'abstrak');
            if (!empty($abstrak)) {
                $model->abstrak =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $abstrak->name));
                $model->abstrak = FileHelper::sanitizeFilename($abstrak->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->abstrak;
                $abstrak->saveAs($path);
            } else {
                $model->abstrak = $old_abstrak;
            }


            $jenisperaturan = JenisPeraturan::findOne($model->jenis_peraturan);
            if (!empty($jenisperaturan)) {
                $model->jenis_peraturan = $jenisperaturan->name;
                // $model->singkatan_jenis = 'UU';
                $model->bentuk_peraturan = $jenisperaturan->name;
            }

            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'Putusan';
                $log->aksi = 'Ubah Putusan';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data putusan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
                Yii::$app->session->setFlash('success', 'Data Putusan berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Deletes an existing Peraturan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('danger', 'Data Putusan berhasil dihapus');
            return $this->redirect(['index']);
        } catch (\yii\db\IntegrityException  $e) {
            Yii::$app->session->setFlash('error', "Data Putusan Tidak Dapat Dihapus Karena Dipakai Modul Lain");
            return $this->redirect(['index']);
        }
    }



    /**
     * Finds the Peraturan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Peraturan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Putusan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*---------- BEGIN TEU -----------------*/

    public function actionTambahPengarang($id)
    {
        $model = new DataPengarang();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Pengarang berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('teu/create-teu', [
                'model' => $model,
                'id' => $id,
            ]);
        }
    }

    public function actionTambahPengarang2($id)
    {
        $model = new Pengarang();
        $teu = new DataPengarang();
        if ($model->load(Yii::$app->request->post()) && $teu->load(Yii::$app->request->post())) {
            $model->status = 'Publish';
            if ($model->save()) {

                $teu->id_dokumen = $id;
                $teu->nama_pengarang = $model->id;
                $teu->save();

                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'Peraturan';
                $log->aksi = 'Tambah Pengarang';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data pengarang pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();

                Yii::$app->session->setFlash('success', 'Data Pengarang berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('teu/create-teu2', [
                'model' => $model,
                'teu' => $teu,
                'id' => $id,
            ]);
        }
    }

    public function actionUbahPengarang($id)
    {
        $model = DataPengarang::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('warning', 'Data Pengarang berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('teu/update-teu', [
                'model' => $model,
            ]);
        }
    }

    public function actionHapusPengarang($id)
    {
        $model = DataPengarang::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('danger', 'Data Pengarang berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }


    /*---------- END TEU -----------------*/

    /*---------- BEGIN SUBYEK -----------------*/

    public function actionTambahSubyek($id)
    {
        $model = new DataSubyek();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Subyek berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('subyek/create-subyek', [
                'model' => $model,
            ]);
        }
    }

    public function actionUbahSubyek($id)
    {
        $model = DataSubyek::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('warning', 'Data Subyek berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('subyek/update-subyek', [
                'model' => $model,
            ]);
        }
    }

    public function actionHapusSubyek($id)
    {
        $model = DataSubyek::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('danger', 'Data Subyek berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }


    /*---------- END SUBYEK -----------------*/

    /*---------- BEGIN LAMPIRAN -----------------*/

    public function actionTambahLampiran($id)
    {
        $model = new DataLampiran();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            $dokumen_lampiran = UploadedFile::getInstance($model, 'dokumen_lampiran');

            if (!empty($dokumen_lampiran)) {
                $model->dokumen_lampiran =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $dokumen_lampiran->name));
                $model->dokumen_lampiran = FileHelper::sanitizeFilename($dokumen_lampiran->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->dokumen_lampiran;
                $dokumen_lampiran->saveAs($path);
            }
            $model->url_lampiran = Yii::getAlias('@imageurl') . '/common/dokumen/' . $model->dokumen_lampiran;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Lampiran berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('lampiran/create-lampiran', [
                'model' => $model,
            ]);
        }
    }

    public function actionUbahLampiran($id)
    {
        $model = DataLampiran::find()->where(['id_dokumen' => $id])->one();
        $old = $model->dokumen_lampiran;
        if ($model->load(Yii::$app->request->post())) {

            $dokumen_lampiran = UploadedFile::getInstance($model, 'dokumen_lampiran');

            if (!empty($dokumen_lampiran)) {
                $model->dokumen_lampiran =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $dokumen_lampiran->name));
                $model->dokumen_lampiran = FileHelper::sanitizeFilename($dokumen_lampiran->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->dokumen_lampiran;
                $dokumen_lampiran->saveAs($path);
                $model->url_lampiran = Yii::getAlias('@imageurl') . '/common/dokumen/' . $model->dokumen_lampiran;
            } else {
                $model->dokumen_lampiran = $old;
            }
            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Peraturan';
                $log->aksi = 'Ubah Lampiran';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data lampiran pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
                Yii::$app->session->setFlash('warning', 'Data Lampiran berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('lampiran/update-lampiran', [
                'model' => $model,
            ]);
        }
    }

    public function actionHapusLampiran($id)
    {
        $model = DataLampiran::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('danger', 'Data Lampiran berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }
    /*---------- END LAMPIRAN -----------------*/

    /*---------- BEGIN PERATURAN TERKAIT -----------------*/

    public function actionTambahPeraturanTerkait($id)
    {
        $model = new PeraturanTerkait();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Peraturan Terkait berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('peraturan-terkait/create-peraturan-terkait', [
                'model' => $model,
            ]);
        }
    }

    public function actionUbahPeraturanTerkait($id)
    {
        $model = PeraturanTerkait::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('warning', 'Data Peraturan Terkait berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('peraturan-terkait/update-peraturan-terkait', [
                'model' => $model,
            ]);
        }
    }

    public function actionHapusPeraturanTerkait($id)
    {
        $model = PeraturanTerkait::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('danger', 'Data Peraturan Terkait berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }


    /*---------- END PERATURAN TERKAIT -----------------*/

    /*---------- BEGIN LAMPIRAN -----------------*/

    // public function actionTambahDokumenTerkait($id)
    // {
    //     $model = new DokumenTerkait();
    //     if ($model->load(Yii::$app->request->post())) {
    //         $model->id_dokumen = $id;
    //         $document_terkait = UploadedFile::getInstance($model, 'document_terkait');

    //         if (!empty($document_terkait)) {
    //             $model->document_terkait =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $document_terkait->name));
    //             $path = Yii::getAlias('@common') . '/dokumen/' . $model->document_terkait;
    //             $document_terkait->saveAs($path);
    //         }

    //         if ($model->save()) {
    //             Yii::$app->session->setFlash('success', 'Data Dokumen terkait berhasil ditambah');
    //             return $this->redirect(['view', 'id' => $id]);
    //         }
    //     } else {
    //         return $this->render('dokumen/create-dokumen', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    public function actionTambahDokumenTerkait($id)
    {
        $model = new DokumenTerkait();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            $dokumen = DataLampiran::findOne($model->urutan);
            if (!empty($dokumen)) {
                $model->document_terkait = $dokumen->dokumen_lampiran;
            }
            // $document_terkait = UploadedFile::getInstance($model, 'document_terkait');

            // if (!empty($document_terkait)) {
            //     $model->document_terkait =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $document_terkait->name));
            //     $path = Yii::getAlias('@common') . '/dokumen/' . $model->document_terkait;
            //     $document_terkait->saveAs($path);
            // }

            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Putusan';
                $log->aksi = 'Dokumen Terkait';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data dokumen terkait putusan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
                Yii::$app->session->setFlash('success', 'Data Dokumen terkait berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('dokumen/create-dokumen-list', [
                'model' => $model,
            ]);
        }
    }

    public function actionTambahDokumenTerkaitList($id)
    {
        $model = new DokumenTerkait();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Dokumen terkait berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('dokumen/create-dokumen-list', [
                'model' => $model,
            ]);
        }
    }

    public function actionUbahDokumenTerkait($id)
    {
        $model = DokumenTerkait::findOne($id);
        $old = $model->document_terkait;
        if ($model->load(Yii::$app->request->post())) {

            $document_terkait = UploadedFile::getInstance($model, 'document_terkait');

            if (!empty($document_terkait)) {
                $model->document_terkait =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $document_terkait->name));
                $model->document_terkait = FileHelper::sanitizeFilename($document_terkait->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->document_terkait;
                $document_terkait->saveAs($path);
            } else {
                $model->document_terkait = $old;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('warning', 'Data Dokumen terkait berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('dokumen/update-dokumen', [
                'model' => $model,
            ]);
        }
    }

    public function actionHapusDokumenTerkait($id)
    {
        $model = DokumenTerkait::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('danger', 'Data Dokumen terkait berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }
    /*---------- END DOKUMEN -----------------*/


    /*---------- BEGIN UJI MATERI -----------------*/

    public function actionTambahHasilUjiMateri($id)
    {
        $model = new HasilUjiMateri();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            $hasil_uji_materi = UploadedFile::getInstance($model, 'hasil_uji_materi');

            if (!empty($hasil_uji_materi)) {
                $model->hasil_uji_materi =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $hasil_uji_materi->name));
                $model->hasil_uji_materi = FileHelper::sanitizeFilename($hasil_uji_materi->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->hasil_uji_materi;
                $hasil_uji_materi->saveAs($path);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Hasil Uji Materi berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('hasil-uji-materi/create-hasil-uji-materi', [
                'model' => $model,
            ]);
        }
    }

    public function actionUbahHasilUjiMateri($id)
    {
        $model = HasilUjiMateri::findOne($id);
        $old = $model->hasil_uji_materi;
        if ($model->load(Yii::$app->request->post())) {

            $hasil_uji_materi = UploadedFile::getInstance($model, 'hasil_uji_materi');

            if (!empty($hasil_uji_materi)) {
                $model->hasil_uji_materi =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $hasil_uji_materi->name));
                $model->hasil_uji_materi = FileHelper::sanitizeFilename($hasil_uji_materi->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->hasil_uji_materi;
                $hasil_uji_materi->saveAs($path);
            } else {
                $model->hasil_uji_materi = $old;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('warning', 'Data Hasil Uji Materi berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('hasil-uji-materi/update-hasil-uji-materi', [
                'model' => $model,
            ]);
        }
    }

    public function actionHapusHasilUjiMateri($id)
    {
        $model = HasilUjiMateri::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('danger', 'Data Hasil Uji Materi berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }
    /*---------- END HASIL UJI MATERI -----------------*/

    /*---------- BEGIN DATA STATUS -----------------*/

    public function actionTambahStatus($id)
    {
        $model = new DataStatus();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data Status berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('status/create-status', [
                'model' => $model,
            ]);
        }
    }

    public function actionUbahStatus($id)
    {
        $model = DataStatus::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('warning', 'Data status berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('status/update-status', [
                'model' => $model,
            ]);
        }
    }

    public function actionHapusStatus($id)
    {
        $model = DataStatus::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('danger', 'Data status berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }


    /*---------- END STATUS -----------------*/

    public function actionDownload($id)
    {

        $path = Yii::getAlias('@common') . '/dokumen/' . $id;
        if (file_exists($path)) {

            return Yii::$app->response->sendFile($path);
        } else {
            throw new NotFoundHttpException("can't find {$id} file");
        }
    }

    public function actionDownloadPeraturan($id)
    {
        $file = DataLampiran::find()->where(['id_dokumen' => $id])->one();


        $path = Yii::getAlias('@common') . '/dokumen/' . $file->dokumen_lampiran;
        if (file_exists($path)) {

            return Yii::$app->response->sendFile($path);
        } else {
            throw new NotFoundHttpException("Tidak dapat menemukan file {$id}, silahkan hubungi admin");
        }
    }


    public function actionDownloadAbstrak($id)
    {
        //$file = DataLampiran::find()->where(['id_dokumen'=>$id])->one();


        $path = Yii::getAlias('@common') . '/dokumen/' . $id;
        if (file_exists($path)) {

            return Yii::$app->response->sendFile($path);
        } else {
            throw new NotFoundHttpException("Tidak dapat menemukan file {$id}, silahkan hubungi admin");
        }
    }

    // public function actionLoaddokumen($q = null, $id = null)
    // {
    //     \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //     $out = ['results' => ['id' => '', 'text' => '']];
    //     if (!is_null($q)) {
    //         $query = new \yii\db\Query();
    //         $query->select('dokumen_lampiran AS id, dokumen_lampiran AS text')
    //             ->from('data_lampiran')
    //             ->where(['like', 'dokumen_lampiran', $q])
    //             ->limit(20);
    //         $command = $query->createCommand();
    //         $data = $command->queryAll();
    //         $out['results'] = array_values($data);
    //         // $data = Peraturan::find($id)->select('id, judul as text')->where(['like', 'judul', $q])->asArray()->all();
    //         // $out['results'] = ArrayHelper::map($data); 
    //     } elseif ($id > 0) {
    //         $out['results'] = ['id' => $id, 'text' => $text];
    //     }
    //     return $out;
    // }

    public function actionLoaddokumen($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query();
            $query->select('id, judul AS text')
                ->from('document')
                ->where(['like', 'judul', $q])
                ->andWhere(['tipe_dokumen' => [2, 3]])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            // $data = Peraturan::find($id)->select('id, judul as text')->where(['like', 'judul', $q])->asArray()->all();
            // $out['results'] = ArrayHelper::map($data); 
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Peraturan::find($id)->judul];
        }
        return $out;
    }

    public function actionGetPeraturan($zipId)
    {
        $location = JenisPeraturan::find()->where(['name' => $zipId])->one();
        echo Json::encode($location);
    }
}
