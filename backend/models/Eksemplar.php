<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "eksemplar".
 *
 * @property int $id
 * @property string|null $id_dokumen
 * @property string|null $kode_eksemplar
 * @property string|null $no_panggil
 * @property string|null $kode_inventaris
 * @property string|null $id_lokasi
 * @property string|null $lokasi_rak
 * @property string|null $tipe_lokasi
 * @property string|null $status_eksemplar
 * @property string|null $nomor_pemesanan
 * @property string|null $tgl_pemesanan
 * @property string|null $tgl_penerimaan
 * @property string|null $agen
 * @property string|null $sumber_perolehan
 * @property string|null $faktur
 * @property string|null $tgl_faktur
 * @property int|null $harga
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 */
class Eksemplar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eksemplar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_eksemplar', 'status_eksemplar'], 'required'],
            [['kode_eksemplar'], 'unique'],
            [['tgl_pemesanan', 'tgl_penerimaan', 'tgl_faktur', 'created_at', 'updated_at'], 'safe'],
            [['id_dokumen', 'harga', 'created_by', 'updated_by'], 'integer'],
            [['kode_eksemplar', 'no_panggil', 'kode_inventaris', 'id_lokasi', 'lokasi_rak', 'tipe_lokasi', 'status_eksemplar', 'nomor_pemesanan', 'agen', 'sumber_perolehan', 'faktur', 'barcode_image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_dokumen' => 'Id Dokumen',
            'kode_eksemplar' => 'Kode Eksemplar',
            'no_panggil' => 'No Panggil',
            'kode_inventaris' => 'Kode Inventaris',
            'id_lokasi' => 'Lokasi',
            'lokasi_rak' => 'Lokasi Rak',
            'tipe_lokasi' => 'Tipe Lokasi',
            'status_eksemplar' => 'Status Eksemplar',
            'nomor_pemesanan' => 'Nomor Pemesanan',
            'tgl_pemesanan' => 'Tgl Pemesanan',
            'tgl_penerimaan' => 'Tgl Penerimaan',
            'agen' => 'Agen',
            'sumber_perolehan' => 'Sumber Perolehan',
            'faktur' => 'Faktur',
            'tgl_faktur' => 'Tgl Faktur',
            'harga' => 'Harga',
            'barcode_image' => 'Gambar Barcode',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }


    public function getMonografi()
    {
        return $this->hasOne(Monografi::className(), ['id' => 'id_dokumen']);
    }


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function getTanggal($tanggal)  // fungsi atau method untuk mengubah hari, tanggal ke format indonesia
    {
        $BulanIndo = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $HariIndo = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $sepparator = '-';
        $parts = explode($sepparator, $tanggal);

        //$hari = $HariIndo[date("w", mktime(0, 0, 0, $parts[1], $parts[2], $parts[0]))]; //mendapatkan hari indonesia
        $tgl   = substr($tanggal, 8, 2); // memisahkan format tanggal menggunakan substring
        $bulan = substr($tanggal, 5, 2); // memisahkan format bulan menggunakan substring   
        $tahun = substr($tanggal, 0, 4); // memisahkan format tahun menggunakan substring

        //$result = $hari .", " .$tgl . " " . $BulanIndo[(int)$bulan] . " ". $tahun;
        $result = $tgl . " " . $BulanIndo[(int)$bulan] . " " . $tahun;
        return ($result);
    }

    public function getUserInput($id)
    {
        $user = User::findOne($id);
        return $user->username;
    }

    public function getJudul($id)
    {
        $monografi = Monografi::findOne($id);
        if (!empty($monografi)) {
            return $monografi->judul;
        } else {
            return '';
        }
    }
}
