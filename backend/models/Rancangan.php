<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "rancangan".
 *
 * @property int $id
 * @property string $tahapan_rancangan
 * @property string $nama_rancangan
 * @property int $jenis_rancangan_id
 * @property int $tahun
 * @property int $program_id
 * @property int $pemrakarsa_id
 * @property int $status_rancangan_id
 * @property int|null $is_publish
 * @property string|null $materi_muatan
 * @property string|null $keterangan
 * @property string|null $file_rancangan
 * @property string|null $file_naskah_akademik
 * @property string|null $tanggal_awal_publish
 * @property string|null $tanggal_akhir_publish
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property string|null $peraturan_id
 *
 * @property JenisRancangan $jenisRancangan
 * @property Pemrakarsa $pemrakarsa
 * @property Program $program
 * @property StatusRancangan $statusRancangan
 */
class Rancangan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rancangan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahapan_rancangan', 'nama_rancangan', 'jenis_rancangan_id', 'tahun', 'program_id', 'pemrakarsa_id', 'status_rancangan_id'], 'required'],
            [['jenis_rancangan_id', 'tahun', 'program_id', 'pemrakarsa_id', 'status_rancangan_id', 'is_publish', 'created_by', 'updated_by'], 'integer'],
            [['materi_muatan', 'keterangan'], 'string'],
            [['tanggal_awal_publish', 'tanggal_akhir_publish', 'created_at', 'updated_at'], 'safe'],
            [['tahapan_rancangan', 'nama_rancangan', 'file_rancangan', 'file_naskah_akademik'], 'string', 'max' => 255],
            [['peraturan_id'], 'string', 'max' => 32],
            [['jenis_rancangan_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisRancangan::className(), 'targetAttribute' => ['jenis_rancangan_id' => 'id']],
            [['pemrakarsa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pemrakarsa::className(), 'targetAttribute' => ['pemrakarsa_id' => 'id']],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['program_id' => 'id']],
            [['status_rancangan_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusRancangan::className(), 'targetAttribute' => ['status_rancangan_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahapan_rancangan' => 'Tahapan Rancangan',
            'nama_rancangan' => 'Nama Rancangan',
            'jenis_rancangan_id' => 'Jenis Rancangan ID',
            'tahun' => 'Tahun',
            'program_id' => 'Program ID',
            'pemrakarsa_id' => 'Pemrakarsa ID',
            'status_rancangan_id' => 'Status Rancangan ID',
            'is_publish' => 'Is Publish',
            'materi_muatan' => 'Materi Muatan',
            'keterangan' => 'Keterangan',
            'file_rancangan' => 'File Rancangan',
            'file_naskah_akademik' => 'File Naskah Akademik',
            'tanggal_awal_publish' => 'Tanggal Awal Publish',
            'tanggal_akhir_publish' => 'Tanggal Akhir Publish',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'peraturan_id' => 'Peraturan ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisRancangan()
    {
        return $this->hasOne(JenisRancangan::className(), ['id' => 'jenis_rancangan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPemrakarsa()
    {
        return $this->hasOne(Pemrakarsa::className(), ['id' => 'pemrakarsa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusRancangan()
    {
        return $this->hasOne(StatusRancangan::className(), ['id' => 'status_rancangan_id']);
    }

    /**
     * {@inheritdoc}
     * @return RancanganQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RancanganQuery(get_called_class());
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

    public function getShare($id)
    {
        $komentar= MasukanMasyarakat::find()->where(['rancangan_id'=>$id,'is_publish'=>1])->count();
        if ($komentar > 0) {
            return $komentar;
            }else{
                return 'tidak ada';
        }
    }
    public function getTotal($id)
    {
        $komentar= MasukanMasyarakat::find()->where(['rancangan_id'=>$id])->count();
        if ($komentar > 0) {
            return $komentar;
            }else{
                return 'tidak ada';
        }
    }
    
}
