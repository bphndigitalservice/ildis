<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property int|null $tipe_dokumen
 * @property string|null $judul
 * @property string|null $teu
 * @property string|null $nomor_peraturan
 * @property string|null $nomor_panggil
 * @property string|null $bentuk_peraturan
 * @property string|null $singkatan_jenis
 * @property string|null $cetakan
 * @property string|null $tempat_terbit
 * @property string|null $penerbit
 * @property string|null $tanggal_penetapan
 * @property string|null $deskripsi_fisik
 * @property string|null $sumber
 * @property string|null $isbn
 * @property string|null $bahasa
 * @property string|null $bidang_hukum
 * @property string|null $nomor_induk_buku
 * @property string|null $jenis_peraturan
 * @property string|null $singkatan_bentuk
 * @property string|null $tipe_koleksi_nomor_eksemplar
 * @property string|null $pola_nomor_eksemplar
 * @property string|null $jumlah_eksemplar
 * @property string|null $kala_terbit
 * @property string|null $tahun_terbit
 * @property string|null $tanggal_dibacakan
 * @property string|null $pernyataan_tanggung_jawab
 * @property string|null $edisi
 * @property string|null $gmd
 * @property string|null $judul_seri
 * @property string|null $klasifikasi
 * @property string|null $info_detil_spesifik
 * @property string|null $abstrak
 * @property string|null $gambar_sampul
 * @property string|null $label
 * @property string|null $sembunyikan_di_opac
 * @property string|null $promosikan_ke_beranda
 * @property string|null $status_terakhir
 * @property string|null $status
 * @property string|null $integrasi
 * @property string|null $_created_by
 * @property string|null $_updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $inisiatif
 * @property string|null $pemrakarsa
 * @property string|null $tanggal_pengundangan
 * @property int|null $daerah
 * @property string|null $penandatanganan
 * @property string|null $lembaga_peradilan
 * @property string|null $pemohon
 * @property string|null $termohon
 * @property string|null $jenis_perkara
 * @property string|null $sub_klasifikasi
 * @property string|null $amar_status
 * @property string|null $berkekuatan_hukum_tetap
 * @property string|null $urusan_pemerintahan
 * @property string|null $catatan_status_peraturan
 * @property int|null $hit_see
 * @property int|null $hit_download
 */
class Putusan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    public function getDokumen()
    {
        return $this->hasOne(DataLampiran::className(), ['id_dokumen' => 'id']);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis_peraturan','nomor_peraturan','tahun_terbit','tanggal_penetapan','judul','bahasa'], 'required'],
            [['tipe_dokumen', 'daerah', 'hit_see', 'hit_download'], 'integer'],
            [['judul', 'teu', 'bentuk_peraturan', 'singkatan_jenis', 'tempat_terbit', 'penerbit', 'sumber', 'bahasa', 'bidang_hukum', 'pernyataan_tanggung_jawab'], 'string'],
            [['tanggal_penetapan', 'tanggal_dibacakan', 'created_at', 'updated_at', 'tanggal_pengundangan'], 'safe'],
            [['nomor_peraturan', 'nomor_panggil', 'cetakan', 'deskripsi_fisik', 'isbn', 'nomor_induk_buku', 'jenis_peraturan', 'singkatan_bentuk', 'tipe_koleksi_nomor_eksemplar', 'pola_nomor_eksemplar', 'jumlah_eksemplar', 'kala_terbit', 'tahun_terbit', 'edisi', 'gmd', 'judul_seri', 'klasifikasi', 'info_detil_spesifik', 'abstrak', 'gambar_sampul', 'label', 'sembunyikan_di_opac', 'promosikan_ke_beranda', 'status_terakhir', 'status', '_created_by', '_updated_by', 'inisiatif', 'pemrakarsa', 'penandatanganan', 'lembaga_peradilan', 'pemohon', 'termohon', 'jenis_perkara', 'sub_klasifikasi', 'amar_status', 'berkekuatan_hukum_tetap', 'urusan_pemerintahan', 'catatan_status_peraturan'], 'string', 'max' => 255],
            [['integrasi'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipe_dokumen' => 'Tipe Dokumen',
            'judul' => 'Judul',
            'teu' => 'Teu',
            'nomor_peraturan' => 'Nomor Peraturan',
            'nomor_panggil' => 'Nomor Panggil',
            'bentuk_peraturan' => 'Bentuk Peraturan',
            'singkatan_jenis' => 'Singkatan Jenis',
            'cetakan' => 'Cetakan',
            'tempat_terbit' => 'Tempat Terbit',
            'penerbit' => 'Penerbit',
            'tanggal_penetapan' => 'Tanggal Penetapan',
            'deskripsi_fisik' => 'Deskripsi Fisik',
            'sumber' => 'Sumber',
            'isbn' => 'Isbn',
            'bahasa' => 'Bahasa',
            'bidang_hukum' => 'Bidang Hukum',
            'nomor_induk_buku' => 'Nomor Induk Buku',
            'jenis_peraturan' => 'Jenis Peraturan',
            'singkatan_bentuk' => 'Singkatan Bentuk',
            'tipe_koleksi_nomor_eksemplar' => 'Tipe Koleksi Nomor Eksemplar',
            'pola_nomor_eksemplar' => 'Pola Nomor Eksemplar',
            'jumlah_eksemplar' => 'Jumlah Eksemplar',
            'kala_terbit' => 'Kala Terbit',
            'tahun_terbit' => 'Tahun Terbit',
            'tanggal_dibacakan' => 'Tanggal Dibacakan',
            'pernyataan_tanggung_jawab' => 'Pernyataan Tanggung Jawab',
            'edisi' => 'Edisi',
            'gmd' => 'Gmd',
            'judul_seri' => 'Judul Seri',
            'klasifikasi' => 'Klasifikasi',
            'info_detil_spesifik' => 'Info Detil Spesifik',
            'abstrak' => 'Abstrak',
            'gambar_sampul' => 'Gambar Sampul',
            'label' => 'Label',
            'sembunyikan_di_opac' => 'Sembunyikan Di Opac',
            'promosikan_ke_beranda' => 'Promosikan Ke Beranda',
            'status_terakhir' => 'Status Terakhir',
            'status' => 'Status',
            'integrasi' => 'Integrasi',
            '_created_by' => 'Created By',
            '_updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'inisiatif' => 'Inisiatif',
            'pemrakarsa' => 'Pemrakarsa',
            'tanggal_pengundangan' => 'Tanggal Pengundangan',
            'daerah' => 'Daerah',
            'penandatanganan' => 'Penandatanganan',
            'lembaga_peradilan' => 'Lembaga Peradilan',
            'pemohon' => 'Pemohon',
            'termohon' => 'Termohon',
            'jenis_perkara' => 'Jenis Perkara',
            'sub_klasifikasi' => 'Sub Klasifikasi',
            'amar_status' => 'Amar Status',
            'berkekuatan_hukum_tetap' => 'Berkekuatan Hukum Tetap',
            'urusan_pemerintahan' => 'Urusan Pemerintahan',
            'catatan_status_peraturan' => 'Catatan Status Peraturan',
            'hit_see' => 'Hit See',
            'hit_download' => 'Hit Download',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PeraturanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PutusanQuery(get_called_class());
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
                'createdByAttribute' => '_created_by',
                'updatedByAttribute' => '_updated_by',
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

    public function getTanggal2($tanggal)  // fungsi atau method untuk mengubah hari, tanggal ke format indonesia
    {
        $BulanIndo = array("","Januari", "Februari", "Maret","April", "Mei", "Juni","Juli", "Agustus", "September","Oktober", "November", "Desember");
        $HariIndo= array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
        $sepparator = '-';
        $parts = explode($sepparator, $tanggal);

        $sepparator2 = ' ';
        $parts2 = explode($sepparator2, $tanggal);

        //$hari = $HariIndo[date("w", mktime(0, 0, 0, $parts[1], $parts[2], $parts[0]))]; //mendapatkan hari indonesia
        $tgl   = substr($tanggal, 8, 2); // memisahkan format tanggal menggunakan substring
        $bulan = substr($tanggal, 5, 2); // memisahkan format bulan menggunakan substring   
        $tahun = substr($tanggal, 0, 4); // memisahkan format tahun menggunakan substring

        //$result = $hari .", " .$tgl . " " . $BulanIndo[(int)$bulan] . " ". $tahun;
        $result = $tgl . " " . $BulanIndo[(int)$bulan] . " ". $tahun;
        return $result. ' Pukul '. $parts2[1];
    } 
     
    public function getUserInput($id) 
    {
        $user = User::findOne($id);
        return $user->username;

    } 
   
}
