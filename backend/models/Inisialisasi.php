<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "inisialisasi".
 *
 * @property int $id
 * @property string|null $nama_inisialisasi
 * @property string|null $gmd
 * @property string|null $tipe_koleksi
 * @property int|null $lokasi
 * @property string|null $lokasi_rak
 * @property string|null $klasifikasi
 * @property string|null $tanggal_dimulai
 * @property string|null $tanggal_berakhir
 * @property int|null $status
 * @property string|null $pelaksana
 */
class Inisialisasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inisialisasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lokasi', 'status'], 'integer'],
            [['tanggal_dimulai', 'tanggal_berakhir'], 'safe'],
            [['nama_inisialisasi', 'lokasi_rak', 'klasifikasi', 'pelaksana'], 'string', 'max' => 255],
            [['gmd', 'tipe_koleksi'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_inisialisasi' => 'Nama Inisialisasi',
            'gmd' => 'Gmd',
            'tipe_koleksi' => 'Tipe Koleksi',
            'lokasi' => 'Lokasi',
            'lokasi_rak' => 'Lokasi Rak',
            'klasifikasi' => 'Klasifikasi',
            'tanggal_dimulai' => 'Tanggal Dimulai',
            'tanggal_berakhir' => 'Tanggal Berakhir',
            'status' => 'Status',
            'pelaksana' => 'Pelaksana',
        ];
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
        $BulanIndo = array("","Januari", "Februari", "Maret","April", "Mei", "Juni","Juli", "Agustus", "September","Oktober", "November", "Desember");
        $HariIndo= array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
        $sepparator = '-';
        $parts = explode($sepparator, $tanggal);

        //$hari = $HariIndo[date("w", mktime(0, 0, 0, $parts[1], $parts[2], $parts[0]))]; //mendapatkan hari indonesia
        $tgl   = substr($tanggal, 8, 2); // memisahkan format tanggal menggunakan substring
        $bulan = substr($tanggal, 5, 2); // memisahkan format bulan menggunakan substring   
        $tahun = substr($tanggal, 0, 4); // memisahkan format tahun menggunakan substring

        //$result = $hari .", " .$tgl . " " . $BulanIndo[(int)$bulan] . " ". $tahun;
        $result = $tgl . " " . $BulanIndo[(int)$bulan] . " ". $tahun;
        return($result);
    }
   
}
