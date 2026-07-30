<?php

namespace backend\controllers;

use Yii;
use backend\models\Monografi;
use backend\models\DokumenPembentukanPuuSearch;
use backend\models\Pengarang;
use backend\models\LogPustakawan;
use backend\models\JenisPeraturan;
use backend\models\DataPengarang;
use backend\models\DataSubyek;
use backend\models\DataLampiran;
use backend\models\Eksemplar;
use common\components\DateHelper;
use common\components\DocumentGroup;
use common\models\DocumentType;
use backend\web\components\FileHelper;
use common\components\SafeDownload;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Json;
use backend\models\EksemplarSearch;

class DokumenPembentukanPuuController extends Controller
{
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
        $searchModel = new DokumenPembentukanPuuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $pengarangProvider = new ActiveDataProvider([
            'query' => DataPengarang::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10],
        ]);

        $subyek = new ActiveDataProvider([
            'query' => DataSubyek::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10],
        ]);

        $lampiran = new ActiveDataProvider([
            'query' => DataLampiran::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10],
        ]);

        $eksemplar = new ActiveDataProvider([
            'query' => Eksemplar::find()->where(['id_dokumen' => $id]),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'teu' => $pengarangProvider,
            'subyek' => $subyek,
            'lampiran' => $lampiran,
            'eksemplar' => $eksemplar,
        ]);
    }

    public function actionCreate()
    {
        $model = new Monografi();
        $log = new LogPustakawan();

        if ($model->load(Yii::$app->request->post())) {
            $abstrak = UploadedFile::getInstance($model, 'abstrak');
            if (!empty($abstrak)) {
                $model->abstrak = FileHelper::sanitizeFilename($abstrak->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->abstrak;
                $abstrak->saveAs($path);
            }

            $cover = UploadedFile::getInstance($model, 'gambar_sampul');
            if (!empty($cover)) {
                $model->gambar_sampul = FileHelper::sanitizeFilename($cover->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->gambar_sampul;
                $cover->saveAs($path);
            }
            $model->save();

            $log->dokumen_id = $model->id;
            $log->controller = 'DokumenPembentukanPuu';
            $log->aksi = 'Tambah Dokumen Penyusunan PUU';
            $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data Dokumen Penyusunan PUU pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
            $log->save();

            Yii::$app->session->setFlash('success', 'Data Dokumen Penyusunan PUU berhasil ditambahkan');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_abstrak = $model->abstrak;
        $old_sampul = $model->gambar_sampul;

        if ($model->load(Yii::$app->request->post())) {

            $abstrak = UploadedFile::getInstance($model, 'abstrak');
            if (!empty($abstrak)) {
                $model->abstrak = FileHelper::sanitizeFilename($abstrak->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->abstrak;
                $abstrak->saveAs($path);
            } else {
                $model->abstrak = $old_abstrak;
            }

            $cover = UploadedFile::getInstance($model, 'gambar_sampul');
            if (!empty($cover)) {
                $model->gambar_sampul = FileHelper::sanitizeFilename($cover->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->gambar_sampul;
                $cover->saveAs($path);
            } else {
                $model->gambar_sampul = $old_sampul;
            }

            $jenisperaturan = JenisPeraturan::findOne($model->jenis_peraturan);
            if (!empty($jenisperaturan)) {
                $model->jenis_peraturan = $jenisperaturan->name;
                $model->bentuk_peraturan = $jenisperaturan->name;
            }

            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'DokumenPembentukanPuu';
                $log->aksi = 'Ubah Dokumen Penyusunan PUU';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data Dokumen Penyusunan PUU pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
                $log->save();
                Yii::$app->session->setFlash('success', 'Data Dokumen Penyusunan PUU berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id]);
            }
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

            $log = new LogPustakawan();
            $log->dokumen_id = $id;
            $log->controller = 'DokumenPembentukanPuu';
            $log->aksi = 'Hapus Dokumen Penyusunan PUU';
            $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data Dokumen Penyusunan PUU pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
            $log->save();

            Yii::$app->session->setFlash('danger', 'Data Dokumen Penyusunan PUU berhasil dihapus');
            return $this->redirect(['index']);
        } catch (\yii\db\IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'Data Dokumen Penyusunan PUU Tidak Dapat Dihapus Karena Dipakai Modul Lain');
            return $this->redirect(['index']);
        }
    }

    public function actionInactive($id)
    {
        $model = $this->findModel($id);
        $model->is_publish = 0;
        if ($model->save(false)) {
            Yii::$app->session->setFlash('danger', 'Verifikasi Dokumen Penyusunan PUU dibatalkan');
        } else {
            Yii::error(
                '[dokumen-pembentukan-puu/inactive] Failed to save model: ' . json_encode($model->getErrors()),
                __METHOD__
            );
            Yii::$app->session->setFlash('error', 'Gagal membatalkan verifikasi Dokumen Penyusunan PUU.');
        }
        return $this->redirect(['index']);
    }

    /*---------- BEGIN TEU -----------------*/

    public function actionTambahPengarang($id)
    {
        $this->findModel($id);
        $model = new DataPengarang();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'DokumenPembentukanPuu';
                $log->aksi = 'Tambah Pengarang';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data pengarang pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
                $log->save();

                Yii::$app->session->setFlash('success', 'Data Pengarang berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('../monografi/teu/create-teu', [
                'model' => $model,
                'id' => $id,
            ]);
        }
    }

    public function actionTambahPengarang2($id)
    {
        $this->findModel($id);
        $model = new Pengarang();
        $pengarangProvider = new DataPengarang();
        if ($model->load(Yii::$app->request->post()) && $pengarangProvider->load(Yii::$app->request->post())) {
            $model->status = 'Publish';
            if ($model->save()) {
                $pengarangProvider->id_dokumen = $id;
                $pengarangProvider->nama_pengarang = $model->id;
                $pengarangProvider->save();

                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'DokumenPembentukanPuu';
                $log->aksi = 'Tambah Pengarang';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data pengarang pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
                $log->save();

                Yii::$app->session->setFlash('success', 'Data Pengarang berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('../monografi/teu/create-teu2', [
                'model' => $model,
                'teu' => $pengarangProvider,
                'id' => $id,
            ]);
        }
    }

    public function actionUbahPengarang($id)
    {
        $model = DataPengarang::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'DokumenPembentukanPuu';
                $log->aksi = 'Ubah Pengarang';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data pengarang pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
                $log->save();
                Yii::$app->session->setFlash('warning', 'Data Pengarang berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('../monografi/teu/update-teu', [
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
        $log->controller = 'DokumenPembentukanPuu';
        $log->aksi = 'Hapus Pengarang';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data pengarang pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
        $log->save();
        Yii::$app->session->setFlash('danger', 'Data Pengarang berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }

    /*---------- END TEU -----------------*/

    /*---------- BEGIN SUBYEK -----------------*/

    public function actionTambahSubyek($id)
    {
        $this->findModel($id);
        $model = new DataSubyek();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'DokumenPembentukanPuu';
                $log->aksi = 'Tambah Subjek';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data subjek pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
                $log->save();

                Yii::$app->session->setFlash('success', 'Data Subyek berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('../monografi/subyek/create-subyek', [
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
                $log->controller = 'DokumenPembentukanPuu';
                $log->aksi = 'Ubah Subjek';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data subjek pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
                $log->save();
                Yii::$app->session->setFlash('warning', 'Data Subyek berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('../monografi/subyek/update-subyek', [
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
        $log->controller = 'DokumenPembentukanPuu';
        $log->aksi = 'Hapus Subjek';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data subjek pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
        $log->save();
        Yii::$app->session->setFlash('danger', 'Data Subyek berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }

    /*---------- END SUBYEK -----------------*/

    /*---------- BEGIN LAMPIRAN -----------------*/

    public function actionTambahLampiran($id)
    {
        $this->findModel($id);
        $model = new DataLampiran();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            $dokumen_lampiran = UploadedFile::getInstance($model, 'dokumen_lampiran');

            if (!empty($dokumen_lampiran)) {
                $model->dokumen_lampiran = FileHelper::sanitizeFilename($dokumen_lampiran->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->dokumen_lampiran;
                $dokumen_lampiran->saveAs($path);
            }
            $model->url_lampiran = Yii::getAlias('@imageurl') . '/common/dokumen/' . $model->dokumen_lampiran;

            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $model->id;
                $log->controller = 'DokumenPembentukanPuu';
                $log->aksi = 'Tambah Lampiran';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data lampiran pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
                $log->save();
                Yii::$app->session->setFlash('success', 'Data Lampiran berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('../monografi/lampiran/create-lampiran', [
                'model' => $model,
            ]);
        }
    }

    public function actionUbahLampiran($id)
    {
        $model = DataLampiran::findOne($id);
        $old = $model->dokumen_lampiran;
        if ($model->load(Yii::$app->request->post())) {
            $dokumen_lampiran = UploadedFile::getInstance($model, 'dokumen_lampiran');

            if (!empty($dokumen_lampiran)) {
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
                $log->controller = 'DokumenPembentukanPuu';
                $log->aksi = 'Ubah Lampiran';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data lampiran pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
                $log->save();
                Yii::$app->session->setFlash('warning', 'Data Lampiran berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('../monografi/lampiran/update-lampiran', [
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
        $log->controller = 'DokumenPembentukanPuu';
        $log->aksi = 'Hapus Lampiran';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data lampiran pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
        $log->save();
        Yii::$app->session->setFlash('danger', 'Data Lampiran berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }

    /*---------- END LAMPIRAN -----------------*/

    /*---------- BEGIN EKSEMPLAR -----------------*/

    public function actionTambahEksemplar($id)
    {
        $monografi = $this->findModel($id);
        $model = new Eksemplar();

        if ($model->load(Yii::$app->request->post())) {
            $model->id_dokumen = $id;
            $model->no_panggil = $monografi->nomor_panggil;

            $barcode = UploadedFile::getInstance($model, 'barcode_image');
            if (!empty($barcode)) {
                $model->barcode_image = FileHelper::sanitizeFilename($barcode->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->barcode_image;
                $barcode->saveAs($path);
            }
            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $id;
                $log->controller = 'DokumenPembentukanPuu';
                $log->aksi = 'Tambah Eksemplar';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan tambah data kode eksemplar pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
                $log->save();

                Yii::$app->session->setFlash('success', 'Data Eksemplar berhasil ditambah');
                return $this->redirect(['view', 'id' => $id]);
            } else {
                Yii::$app->session->setFlash('error', 'Data Kode Eksemplar sudah ada');
                return $this->render('../monografi/eksemplar/create-eksemplar', [
                    'model' => $model,
                    'id' => $id,
                ]);
            }
        } else {
            return $this->render('../monografi/eksemplar/create-eksemplar', [
                'model' => $model,
                'id' => $id,
            ]);
        }
    }

    public function actionUbahEksemplar($id)
    {
        $model = Eksemplar::findOne($id);
        $old_barcode = $model->barcode_image;
        if ($model->load(Yii::$app->request->post())) {
            $barcode = UploadedFile::getInstance($model, 'barcode_image');
            if (!empty($barcode)) {
                $model->barcode_image = FileHelper::sanitizeFilename($barcode->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->barcode_image;
                $barcode->saveAs($path);
            } else {
                $model->barcode_image = $old_barcode;
            }

            if ($model->save()) {
                $log = new LogPustakawan();
                $log->dokumen_id = $model->id_dokumen;
                $log->controller = 'DokumenPembentukanPuu';
                $log->aksi = 'Ubah Eksemplar';
                $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan ubah data eksemplar pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
                $log->save();
                Yii::$app->session->setFlash('warning', 'Data Eksemplar berhasil diubah');
                return $this->redirect(['view', 'id' => $model->id_dokumen]);
            }
        } else {
            return $this->render('../monografi/eksemplar/update-eksemplar', [
                'model' => $model,
            ]);
        }
    }

    public function actionHapusEksemplar($id)
    {
        $model = Eksemplar::findOne($id);
        $model->delete();
        $log = new LogPustakawan();
        $log->dokumen_id = $model->id_dokumen;
        $log->controller = 'DokumenPembentukanPuu';
        $log->aksi = 'Hapus Eksemplar';
        $log->keterangan = 'User ' . \Yii::$app->user->identity->username . ' melakukan hapus data eksemplar pada ' . DateHelper::formatIndonesian(date('Y-m-d H:i:s'));
        $log->save();
        Yii::$app->session->setFlash('danger', 'Data Eksemplar berhasil dihapus');
        return $this->redirect(['view', 'id' => $model->id_dokumen]);
    }

    /*---------- END EKSEMPLAR -----------------*/

    public function actionCetak()
    {
        $list = Yii::$app->request->post('list', '');
        if (!empty($list)) {
            $ids = array_map('intval', explode(',', $list));
            $searchModel = new EksemplarSearch();
            $dataProvider = $searchModel->get_eksemplarByIds($ids);
            $result = $dataProvider->getModels();

            if (empty($result)) {
                Yii::$app->session->setFlash('error', 'Tidak ada eksemplar pada judul buku yang telah dipilih.');
            }

            return $this->render('../monografi/eksemplar/barcode', [
                'result' => $result,
            ]);
        } else {
            Yii::$app->session->setFlash('error', 'Tidak ada data yang dipilih.');
        }
    }

    public function actionDownload($id)
    {
        return SafeDownload::sendFile('@common/dokumen', $id);
    }

    public function actionDownloadPeraturan($id)
    {
        $file = DataLampiran::find()->where(['id_dokumen' => $id])->one();
        if (!$file) {
            throw new NotFoundHttpException('Data lampiran tidak ditemukan.');
        }
        return SafeDownload::sendFile('@common/dokumen', $file->dokumen_lampiran);
    }

    public function actionDownloadAbstrak($id)
    {
        return SafeDownload::sendFile('@common/dokumen', $id);
    }

    public function actionGetPeraturan($zipId)
    {
        $location = JenisPeraturan::find()->where(['name' => $zipId])->one();
        echo Json::encode($location);
    }

    protected function findModel($id)
    {
        $model = Monografi::findOne($id);
        if (!$model || !in_array($model->jenis_peraturan, DocumentType::groupTypeNames(DocumentGroup::LEGISLATION_FORMATION))) {
            throw new NotFoundHttpException('Dokumen tidak ditemukan.');
        }
        return $model;
    }
}