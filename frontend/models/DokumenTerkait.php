<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "document_terkait".
 *
 * @property int $id
 * @property int|null $id_dokumen
 * @property string|null $document_terkait
 * @property string|null $status_docter
 * @property string|null $catatan_docter
 * @property int|null $integrasi
 * @property int|null $urutan
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $_created_by
 * @property string|null $_updated_by
 */
class DokumenTerkait extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_terkait';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dokumen', 'integrasi', 'urutan'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['document_terkait', 'status_docter', 'catatan_docter', '_created_by', '_updated_by'], 'string', 'max' => 255],
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
            'document_terkait' => 'Document Terkait',
            'status_docter' => 'Status Docter',
            'catatan_docter' => 'Catatan Docter',
            'integrasi' => 'Integrasi',
            'urutan' => 'Urutan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            '_created_by' => 'Created By',
            '_updated_by' => 'Updated By',
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
