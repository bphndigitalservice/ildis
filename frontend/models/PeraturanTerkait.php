<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "peraturan_terkait".
 *
 * @property int $id
 * @property int|null $id_dokumen
 * @property string|null $peraturan_terkait
 * @property string|null $status_perter
 * @property string|null $catatan_perter
 * @property int|null $integrasi
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $urutan
 * @property string|null $_created_by
 * @property string|null $_updated_by
 */
class PeraturanTerkait extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peraturan_terkait';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dokumen', 'integrasi', 'urutan'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['peraturan_terkait', 'status_perter', 'catatan_perter', '_created_by', '_updated_by'], 'string', 'max' => 255],
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
            'peraturan_terkait' => 'Peraturan Terkait',
            'status_perter' => 'Status Perter',
            'catatan_perter' => 'Catatan Perter',
            'integrasi' => 'Integrasi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'urutan' => 'Urutan',
            '_created_by' => 'Created By',
            '_updated_by' => 'Updated By',
        ];
    }

    public function getPeraturan()
    {
        return $this->hasOne(Dokumen::className(), ['id' => 'peraturan_terkait']);
    }

    public function getJudul($id)
    {
        $peraturan = Dokumen::findOne($id);
        return  $peraturan->judul;
//        return $this->hasOne(Peraturan::className(), ['id' => 'id_dokumen']);
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
