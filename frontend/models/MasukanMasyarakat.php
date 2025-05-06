<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "masukan_masyarakat".
 *
 * @property int $id
 * @property int $rancangan_id
 * @property string $nama
 * @property string $email
 * @property string $pekerjaan
 * @property string $instansi
 * @property string $komentar
 * @property string $masukan_mewakili
 * @property string|null $file_pendukung
 * @property int|null $is_publish
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 */
class MasukanMasyarakat extends \yii\db\ActiveRecord
{
     public $verifyCode;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'masukan_masyarakat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rancangan_id', 'nama', 'email', 'pekerjaan', 'instansi', 'komentar', 'masukan_mewakili'], 'required'],
            [['rancangan_id', 'is_publish', 'created_by', 'updated_by'], 'integer'],
            [['komentar', 'masukan_mewakili'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama', 'email', 'pekerjaan', 'instansi', 'file_pendukung'], 'string', 'max' => 255],
             ['verifyCode', 'captcha'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rancangan_id' => 'Rancangan ID',
            'nama' => 'Nama',
            'email' => 'Email',
            'pekerjaan' => 'Pekerjaan',
            'instansi' => 'Instansi',
            'komentar' => 'Komentar',
            'masukan_mewakili' => 'Masukan Mewakili',
            'file_pendukung' => 'File Pendukung',
            'is_publish' => 'Is Publish',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * {@inheritdoc}
     * @return MasukanMasyarakatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MasukanMasyarakatQuery(get_called_class());
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
