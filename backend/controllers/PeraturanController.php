<?php

namespace backend\controllers;

use Yii;
use backend\models\Peraturan;
use backend\models\Pengarang;
use backend\models\LogPustakawan;
use backend\models\JenisPeraturan;
use backend\models\DataPengarang;
use backend\models\DataSubyek;
use backend\models\DataStatus;
use backend\models\Abstrak;
use backend\models\DataLampiran;
use backend\models\HasilUjiMateri;
use backend\models\DokumenTerkait;
use backend\models\PeraturanTerkait;
use backend\models\PeraturanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\web\components\FileHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\Html;
use kartik\mpdf\Pdf;


/**
 * PeraturanController implements the CRUD actions for Peraturan model.
 */
class PeraturanController extends Controller
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
        $searchModel = new PeraturanSearch();
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


        $abstrak = Abstrak::find()->where(['id_dokumen' => $id])->one();

        $status = new ActiveDataProvider([
            'query' => DataStatus::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10]
        ]);

        $log = new ActiveDataProvider([
            'query' => LogPustakawan::find()->where(['dokumen_id' => $id]),
            'pagination' => ['pageSize' => 200]
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'teu' => $teu,
            'subyek' => $subyek,
            'lampiran' => $lampiran,
            'peraturan' => $peraturan,
            'dokumen' => $dokumen,
            'status' => $status,
            'abstrak' => $abstrak,
            'ujimateri' => $ujimateri,
            'log' => $log,

        ]);
    }


    public function actionCreate2()
    {
        $model = new Peraturan();
        $lampiran = new DataLampiran();
        $teu = new DataPengarang();
        $log = new LogPustakawan();

        if ($model->load(Yii::$app->request->post()) && $lampiran->load(Yii::$app->request->post()) && $teu->load(Yii::$app->request->post())) {

            $model->tipe_dokumen = 1;
            $jenisperaturan = JenisPeraturan::findOne($model->jenis_peraturan);
            if (!empty($jenisperaturan)) {
                $model->jenis_peraturan = $jenisperaturan->name;
                $model->singkatan_jenis = $jenisperaturan->singkatan;
                // $model->bentuk_peraturan = $jenisperaturan->name;
            }

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

            $teu->id_dokumen = $model->id;
            $teu->save();


            $log->dokumen_id = $model->id;
            $log->controller = 'Peraturan';
            $log->aksi = 'Tambah Peraturan';
            $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
            $log->save();

            Yii::$app->session->setFlash('success', 'Data Peraturan berhasil ditambahkan');
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
            Yii::$app->session->setFlash('warning', 'Pastikan Data Tajuk Entry Utama Sudah diinput terlebih dahulu');
            return $this->render('create', [
                'model' => $model,
                'lampiran' => $lampiran,
                'teu' => $teu,

            ]);
        }
    }

    public function actionCreate()
    {
        $model = new Peraturan();
        $lampiran = new DataLampiran();
        $teu = new DataPengarang();
        $log = new LogPustakawan();

        if ($model->load(Yii::$app->request->post()) && $lampiran->load(Yii::$app->request->post())) {

            $model->tipe_dokumen = 1;
            $jenisperaturan = JenisPeraturan::findOne($model->jenis_peraturan);
            if (!empty($jenisperaturan)) {
                $model->jenis_peraturan = $jenisperaturan->name;
                $model->singkatan_jenis = $jenisperaturan->singkatan;
                $model->dokumen_type_id = $jenisperaturan->id;
                // $model->bentuk_peraturan = $jenisperaturan->name;
            }

            $abstrak = UploadedFile::getInstance($model, 'abstrak');
            if (!empty($abstrak)) {
                $model->abstrak =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $abstrak->name));
                $model->abstrak = FileHelper::sanitizeFilename($abstrak->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->abstrak;
                $abstrak->saveAs($path);
            }

            $model->jenis_peraturan = htmlentities($model->jenis_peraturan);
            $model->bentuk_peraturan = htmlentities($model->bentuk_peraturan);
            $model->judul = htmlentities($model->judul);
            $model->nomor_peraturan = htmlentities($model->nomor_peraturan);
            $model->tahun_terbit = htmlentities($model->tahun_terbit);
            $model->tempat_terbit = htmlentities($model->tempat_terbit);
            $model->tanggal_penetapan = htmlentities($model->tanggal_penetapan);
            $model->penandatanganan = htmlentities($model->penandatanganan);
            $model->tanggal_pengundangan = htmlentities($model->tanggal_pengundangan);
            $model->pemrakarsa = htmlentities($model->pemrakarsa);
            $model->sumber = htmlentities($model->sumber);
            
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
            $log->controller = 'Peraturan';
            $log->aksi = 'Tambah Peraturan';
            $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
            $log->save();

            Yii::$app->session->setFlash('success', 'Data Peraturan berhasil ditambahkan');
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

            return $this->render('create2', [
                'model' => $model,
                'lampiran' => $lampiran,


            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_abstrak = $model->abstrak;

        if ($model->load(Yii::$app->request->post())) {

            $jenisperaturan = JenisPeraturan::find()->where(['name' => $model->jenis_peraturan])->one();
            if (!empty($jenisperaturan)) {
                $model->jenis_peraturan = $jenisperaturan->name;
                $model->singkatan_jenis = $jenisperaturan->singkatan;
            }

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
            
            $model->jenis_peraturan = htmlentities($model->jenis_peraturan);
            $model->bentuk_peraturan = htmlentities($model->bentuk_peraturan);
            $model->judul = htmlentities($model->judul);
            $model->nomor_peraturan = htmlentities($model->nomor_peraturan);
            $model->tahun_terbit = htmlentities($model->tahun_terbit);
            $model->tempat_terbit = htmlentities($model->tempat_terbit);
            $model->tanggal_penetapan = htmlentities($model->tanggal_penetapan);
            $model->penandatanganan = htmlentities($model->penandatanganan);
            $model->tanggal_pengundangan = htmlentities($model->tanggal_pengundangan);
            $model->pemrakarsa = htmlentities($model->pemrakarsa);
            $model->sumber = htmlentities($model->sumber);

            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'Peraturan';
                $log->aksi = 'Ubah Peraturan';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
                Yii::$app->session->setFlash('success', 'Data Peraturan berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    //-- Update JDIH 2022 START -->
    public function actionUbahAbstrak($id)
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



            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'Peraturan';
                $log->aksi = 'Ubah Peraturan';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
                Yii::$app->session->setFlash('success', 'Data Peraturan berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update-abstrak', [
                'model' => $model,
            ]);
        }
    }

    public function actionHapusAbstrak($id)
    {
        $model = Abstrak::findOne($id);
        $model->delete();
        $log = new LogPustakawan();
        $log->dokumen_id = $model->id_dokumen;
        $log->controller = 'Peraturan';
        $log->aksi = 'Hapus Abstrak';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data abstrak peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
        $log->save();
        Yii::$app->session->setFlash('danger', 'Data Abstrak berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }

    //-- Update JDIH 2022 END --

    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();

            $log = new LogPustakawan();
            $log->dokumen_id = $id;
            $log->controller = 'Peraturan';
            $log->aksi = 'Hapus Peraturan';
            $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
            $log->save();

            Yii::$app->session->setFlash('danger', 'Data Peraturan berhasil dihapus');
            return $this->redirect(['index']);
        } catch (\yii\db\IntegrityException  $e) {
            Yii::$app->session->setFlash('error', "Data Peraturan Tidak Dapat Dihapus Karena Dipakai Modul Lain");
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id)
    {
        if (($model = Peraturan::findOne($id)) !== null) {
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
            //$model->id_dokumen=$id;
            if ($model->save()) {

                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Peraturan';
                $log->aksi = 'Ubah teu';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data teu monografi pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
                Yii::$app->session->setFlash('warning', 'Data Pengarang berhasil diubah');
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
        $log = new LogPustakawan();
        $log->dokumen_id = $model->id_dokumen;
        $log->controller = 'Peraturan';
        $log->aksi = 'Hapus  Pengarang';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data teu peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
        $log->save();
        Yii::$app->session->setFlash('danger', 'Data Pengarang berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }


    /*---------- END TEU -----------------*/

    /*---------- BEGIN SUBYEK -----------------*/

    public function actionTambahSubyek($id)
    {
        $peraturan = Peraturan::findOne($id);
        $model = new DataSubyek();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            if ($model->save()) {

                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'Peraturan';
                $log->aksi = 'Tambah Subjek';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data subjek peraturan ' . Html::a($peraturan->judul, ['/peraturan/view', 'id' => $id], ['target' => '_blank']) . ' pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();

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

                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Peraturan';
                $log->aksi = 'Ubah Subjek';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data subjek peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
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
        $log = new LogPustakawan();
        $log->dokumen_id = $model->id_dokumen;
        $log->controller = 'Peraturan';
        $log->aksi = 'Hapus Subjek';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data subjek peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
        $log->save();
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

                $log = new LogPustakawan();
                $log->dokumen_id = $model->id;
                $log->controller = 'Peraturan';
                $log->aksi = 'Tambah Lampiran';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data lampiran pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
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
        $log = new LogPustakawan();
        $log->dokumen_id = $model->id_dokumen;
        $log->controller = 'Peraturan';
        $log->aksi = 'Hapus Lampiran';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data lampiran peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
        $log->save();
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

                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Peraturan';
                $log->aksi = 'Tambah Peraturan Terkait';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data peraturan terkait pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
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
                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Peraturan';
                $log->aksi = 'Ubah Peraturan Terkait';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data peraturan terkait pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
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
        $log = new LogPustakawan();
        $log->dokumen_id = $model->id_dokumen;
        $log->controller = 'Peraturan';
        $log->aksi = 'Hapus Peraturan Terkait';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data peraturan terkait pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
        $log->save();
        Yii::$app->session->setFlash('danger', 'Data Peraturan Terkait berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }


    /*---------- END PERATURAN TERKAIT -----------------*/

    /*---------- BEGIN LAMPIRAN -----------------*/

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
                $log->controller = 'Peraturan';
                $log->aksi = 'Dokumen Terkait';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data dokumen terkait peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
                Yii::$app->session->setFlash('success', 'Data Dokumen terkait berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('dokumen/create-dokumen', [
                'model' => $model,
            ]);
        }
    }

    public function actionTambahDokumenTerkaitList($id)
    {
        $model = new DokumenTerkait();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;

            $dokumen = DataLampiran::find()->where(['id_dokumen' => $model->id_dokumen_terkait])->one();
            if (!empty($dokumen)) {
                $model->document_terkait = $dokumen->dokumen_lampiran;
                $model->id_dokumen_terkait = $dokumen->id_dokumen;
            }
            //$model->urutan
            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'Peraturan';
                $log->aksi = 'Tambah Dokumen Terkait';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data dokumen terkait pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
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
            if ($model->save(false)) {

                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Peraturan';
                $log->aksi = 'Ubah Dokumen Terkait';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data dokumen terkait peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
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
        $log = new LogPustakawan();
        $log->dokumen_id = $model->id_dokumen;
        $log->controller = 'Peraturan';
        $log->aksi = 'Hapus Dokumen Terkait';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data dokumen terkait peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
        $log->save();
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
                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Peraturan';
                $log->aksi = 'Tambah Uji Materi';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data uji materi pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
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
                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Peraturan';
                $log->aksi = 'Ubah Uji Materi';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data uji materi peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
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
        $log = new LogPustakawan();
        $log->dokumen_id = $model->id_dokumen;
        $log->controller = 'Peraturan';
        $log->aksi = 'Hapus Uji Materi';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data uji materi pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
        $log->save();
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

                switch ($model->status_peraturan) {
                    case 'dicabut':
                        $model2 = new DataStatus();
                        $model2->id_dokumen = $_POST['DataStatus']['id_dokumen_target'];
                        $model2->status_peraturan = 'mencabut';
                        $model2->id_dokumen_target = $id;
                        $model2->tanggal_perubahan = $_POST['DataStatus']['tanggal_perubahan'];
                        $model2->save(false);

                        $model3 = Peraturan::findOne($id);

                        if (!empty($model3)) {
                            $model3->status = 'Tidak Berlaku';
                            $model3->status_terakhir = 'dicabut';
                            $model3->save(false);
                        }

                        $model4 = Peraturan::findOne($model2->id_dokumen);

                        if (!empty($model4)) {
                            $model4->status = 'Berlaku';
                            $model4->status_terakhir = 'mencabut';
                            $model4->save(false);
                        }
                        break;

                    case 'mencabut':

                        $peraturan = Peraturan::findOne($model->id_dokumen_target);
                        if (!empty($peraturan)) {
                            if ($peraturan->status_terakhir == 'Tidak Berlaku') {
                                Yii::$app->session->setFlash('danger', 'peraturan sudah tidak berlaku');
                                return $this->redirect(['view', 'id' => $id]);
                            } else {
                                $model2 = new DataStatus();
                                $model2->id_dokumen = $_POST['DataStatus']['id_dokumen_target'];
                                $model2->status_peraturan = 'dicabut';
                                $model2->id_dokumen_target = $id;
                                $model2->tanggal_perubahan = $_POST['DataStatus']['tanggal_perubahan'];
                                $model2->save(false);

                                $model3 = Peraturan::findOne($id);
                                $model3->status = 'Berlaku';
                                $model3->status_terakhir = 'mencabut';
                                $model3->save(false);

                                $model4 = Peraturan::findOne($model2->id_dokumen);

                                if (!empty($model4)) {
                                    $model4->status = 'Tidak Berlaku';
                                    $model4->status_terakhir = 'dicabut';
                                    $model4->save(false);
                                }
                            }
                        }
                        break;

                    case 'mengubah':
                        $model2 = new DataStatus();
                        $peraturan = Peraturan::findOne($model->id_dokumen_target);
                        if (!empty($peraturan)) {
                            if ($peraturan->status == 'Tidak Berlaku') {
                                Yii::$app->session->setFlash('danger', 'peraturan sudah tidak berlaku');
                                return $this->redirect(['view', 'id' => $id]);
                            } else {
                                $model2->id_dokumen = $_POST['DataStatus']['id_dokumen_target'];
                                $model2->status_peraturan = 'diubah';
                                $model2->id_dokumen_target = $id;
                                $model2->tanggal_perubahan = $_POST['DataStatus']['tanggal_perubahan'];
                                $model2->save(false);

                                $model3 = Peraturan::findOne($id);
                                $model3->status = 'Berlaku';
                                $model3->status_terakhir = 'Mengubah';
                                $model3->save(false);

                                $model4 = Peraturan::findOne($model2->id_dokumen);

                                if (!empty($model4)) {
                                    $model4->status = 'Berlaku';
                                    $model4->status_terakhir = 'Diubah';
                                    $model4->save(false);
                                }
                            }
                        }
                        break;

                    case 'diubah':
                        $model2 = new DataStatus();
                        $model2->id_dokumen = $_POST['DataStatus']['id_dokumen_target'];
                        $model2->status_peraturan = 'mengubah';
                        $model2->id_dokumen_target = $id;
                        $model2->tanggal_perubahan = $_POST['DataStatus']['tanggal_perubahan'];
                        $model2->save(false);

                        $model3 = Peraturan::findOne($id);
                        $model3->status = 'Berlaku';
                        $model3->status_terakhir = 'diubah';
                        $model3->save(false);

                        $model4 = Peraturan::findOne($model2->id_dokumen);

                        if (!empty($model4)) {
                            $model4->status = 'Berlaku';
                            $model4->status_terakhir = 'mengubah';
                            $model4->save(false);
                        }
                        break;

                    case '-':
                        $model2 = new DataStatus();
                        $model2->id_dokumen = 0;
                        $model2->status_peraturan = '-';
                        $model2->id_dokumen_target = $id;
                        $model2->tanggal_perubahan = $_POST['DataStatus']['tanggal_perubahan'];
                        $model2->save(false);

                        $model3 = Peraturan::findOne($id);
                        $model3->status = '-';
                        $model3->status_terakhir = '-';
                        $model3->save(false);

                        $model4 = Peraturan::findOne($model2->id_dokumen);

                        if (!empty($model4)) {
                            $model4->status = '-';
                            $model4->status_terakhir = '-';
                            $model4->save(false);
                        }
                        break;
                }

                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Peraturan';
                $log->aksi = 'Tambah Status';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data status peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
                Yii::$app->session->setFlash('success', 'Data Status berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            Yii::$app->session->setFlash('danger', 'harap diperhatikan, Perubahan status berefek pada peraturan yang dituju dan status dari peraturan');
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
                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'Peraturan';
                $log->aksi = 'Ubah Status';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data status peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
                $log->save();
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

        switch ($model->status_peraturan) {
            case 'dicabut':


                $model3 = Peraturan::findOne($model->id_dokumen);
                $model3->status = 'Berlaku';
                $model3->status_terakhir = '';
                $model3->save(false);

                $model4 = Peraturan::findOne($model->id_dokumen_target);

                if (!empty($model4)) {
                    $model4->status = 'Berlaku';
                    $model4->status_terakhir = '';
                    $model4->save(false);
                }
                break;

            case 'mencabut':


                $model3 = Peraturan::findOne($model->id_dokumen);
                $model3->status = 'Berlaku';
                $model3->status_terakhir = '';
                $model3->save(false);

                $model4 = Peraturan::findOne($model->id_dokumen_target);

                if (!empty($model4)) {
                    $model4->status = 'Berlaku';
                    $model4->status_terakhir = '';
                    $model4->save(false);
                }
                break;

            case 'mengubah':

                $model3 = Peraturan::findOne($model->id_dokumen);
                $model3->status = 'Berlaku';
                $model3->status_terakhir = 'Berlaku';
                $model3->save(false);

                $model4 = Peraturan::findOne($model->id_dokumen_target);

                if (!empty($model4)) {
                    $model4->status = 'Berlaku';
                    $model4->status_terakhir = 'Berlaku';
                    $model4->save(false);
                }
                break;
                break;

            case 'diubah':

                $model3 = Peraturan::findOne($model->id_dokumen);
                $model3->status = 'Berlaku';
                $model3->status_terakhir = 'Berlaku';
                $model3->save(false);

                $model4 = Peraturan::findOne($model->id_dokumen_target);

                if (!empty($model4)) {
                    $model4->status = 'Berlaku';
                    $model4->status_terakhir = 'Berlaku';
                    $model4->save(false);
                }
                break;
        }


        $log = new LogPustakawan();
        $log->dokumen_id = $model->id_dokumen;
        $log->controller = 'Peraturan';
        $log->aksi = 'Hapus Status';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data status peraturan pada ' . $log->getTanggal2(date("Y-m-d H:i:s"));
        $log->save();
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

    public function actionLoaddokumen($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query();
            $query->select('dokumen_lampiran AS id, dokumen_lampiran AS text')
                ->from('data_lampiran')
                ->where(['like', 'dokumen_lampiran', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            // $data = Peraturan::find($id)->select('id, judul as text')->where(['like', 'judul', $q])->asArray()->all();
            // $out['results'] = ArrayHelper::map($data); 
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => $text];
        }
        return $out;
    }

    public function actionJenis($id)
    {

        $dokumen = \backend\models\JenisPeraturan::find()->where(['id' => $id])->one();
        $rows = \backend\models\JenisPeraturan::find()->where(['parent_id' => $id])->all();
        //echo "<option> Pilih Bentuk Peraturan Dokumen </option>";
        //echo "<option></option>";
        if (count($rows) > 0) {
            foreach ($rows as $branch) {
                echo "<option value'" . $branch->name . "'>" . $branch->singkatan . "</option>";
            }
        } else {
            echo "<option value'" . $dokumen->name . "'>" . $dokumen->singkatan . "</option>";
        }
    }


    public function actionJenis2($id)
    {

        $dokumen = \backend\models\JenisPeraturan::find()->where(['name' => $id])->one();
        $rows = \backend\models\JenisPeraturan::find()->where(['parent_id' => $dokumen->id])->all();
        //echo "<option> Pilih Bentuk Peraturan Dokumen </option>";
        //echo "<option></option>";
        if (count($rows) > 0) {
            foreach ($rows as $branch) {
                echo "<option value'" . $branch->name . "'>" . $branch->singkatan . "</option>";
            }
        } else {
            echo "<option value'" . $dokumen->name . "'>" . $dokumen->singkatan . "</option>";
        }
    }

    public function actionGetPeraturan($zipId)
    {
        $location = JenisPeraturan::find()->where(['name' => $zipId])->one();
        echo Json::encode($location);
    }


    public function actionLoadperaturan($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query();
            $query->select('id, judul AS text')
                ->from('document')
                ->where(['like', 'judul', $q])
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

    public function actionDokumenonly($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query();
            $query->select('id, judul AS text')
                ->from('document')
                ->where(['like', 'judul', $q])
                ->andWhere(['tipe_dokumen' => [2, 3, 4]])
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


    public function actionLoadteu($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query();
            $query->select('id, name AS text')
                ->from('pengarang')
                ->where(['like', 'name', $q])
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

    // ============ abstrak ====================/

    public function actionTambahAbstrak($id)
    {
        $model = new Abstrak();
        $peraturan = Peraturan::findOne($id);
        $datasubyek = DataSubyek::find()->where(['id_dokumen' => $id])->all();
        if (!empty($datasubyek)) {
            $simpan = '';
            foreach ($datasubyek as $data) {

                $simpan = $simpan . $data->subyek . '-';
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->subjek =  $simpan;
            $model->tahun = $peraturan->tahun_terbit;
            $model->id_dokumen = $id;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('abstrak/create-tambah-abstrak', [
                'model' => $model,
                'peraturan' => $peraturan,
                'simpan' => $simpan,
                'id' => $id,
            ]);
        }
    }

    public function actionEditAbstrak($id)
    {
        $model = Abstrak::findOne($id);
        //   $peraturan = Peraturan::findOne($id);

        $datasubyek = DataSubyek::find()->where(['id_dokumen' => $model->id_dokumen])->all();
        if (!empty($datasubyek)) {
            $simpan = '';
            foreach ($datasubyek as $data) {

                $simpan = $simpan . $data->subyek . '-';
            }
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->subjek = $simpan;

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('abstrak/ubah-abstrak', [
                'model' => $model,
                'simpan' => $simpan,

            ]);
        }
    }

    public function actionCetakAbstrak($id)
    {

        $model = Abstrak::findOne($id);
        //  $komentar = Komentar::find()->where(['partisipasi_id' => $id])->all();
        $content = $this->renderPartial('abstrak/cetak-abstrak', ['model' => $model]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_FOLIO,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,

            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.css',

            'marginFooter' => 5,

            'options' => ['title' => 'Krajee Report Title'],
            'methods' => []
        ]);

        return $pdf->render();
    }
    public function actionInactive($id = null)
    {
        if ($id != null) {
            $model = $this->findModel($id);
            $model->is_publish = 0;
            if ($model->save()) {
                Yii::$app->session->setFlash('danger', 'Produk Hukum tidak  diverifikasi');
                return $this->redirect(['index']);
            } else {
                print_r($model->getErrors());
            }
        } else {
            $model = $this->findModel(\Yii::$app->user->identity->id);
            $model->is_publish = 0;
            $model->save();
            if ($model->save()) {
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('success', 'Account inactive');
                return $this->goHome();
            }
        }
    }
}
