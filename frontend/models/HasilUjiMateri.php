<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "hasil_uji_materi".
 *
 * @property int $id
 * @property int $id_dokumen
 * @property string|null $hasil_uji_materi
 * @property string|null $status_hasum
 * @property string|null $catatan_hasum
 * @property int|null $integrasi
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $urutan
 * @property string|null $_updated_by
 * @property string|null $_created_by
 */
class HasilUjiMateri extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hasil_uji_materi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dokumen'], 'required'],
            [['id_dokumen', 'integrasi', 'urutan'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['hasil_uji_materi', 'status_hasum', 'catatan_hasum', '_updated_by', '_created_by'], 'string', 'max' => 255],
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
            'hasil_uji_materi' => 'Hasil Uji Materi',
            'status_hasum' => 'Status Hasum',
            'catatan_hasum' => 'Catatan Hasum',
            'integrasi' => 'Integrasi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'urutan' => 'Urutan',
            '_updated_by' => 'Updated By',
            '_created_by' => 'Created By',
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
