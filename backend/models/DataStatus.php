<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "data_status".
 *
 * @property int $id
 * @property int $id_dokumen
 * @property string|null $status_peraturan
 * @property string|null $id_dokumen_target
 * @property string|null $catatan_status_peraturan
 * @property string|null $tanggal_perubahan
 * @property string|null $status
 * @property int|null $integrasi
 * @property string|null $_created_by
 * @property string|null $_updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $urutan
 */
class DataStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dokumen',  'status_peraturan'], 'required'],
            [['id_dokumen', 'integrasi', 'urutan'], 'integer'],
            [['catatan_status_peraturan'], 'string'],
            [['tanggal_perubahan', 'created_at', 'updated_at'], 'safe'],
            [['status_peraturan', 'id_dokumen_target', 'status', '_created_by', '_updated_by'], 'string', 'max' => 255],
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
            'status_peraturan' => 'Status Peraturan',
            'id_dokumen_target' => 'Id Dokumen Target',
            'catatan_status_peraturan' => 'Catatan Status Peraturan',
            'tanggal_perubahan' => 'Tanggal Perubahan',
            'status' => 'Status',
            'integrasi' => 'Integrasi',
            '_created_by' => 'Created By',
            '_updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'urutan' => 'Urutan',
        ];
    }

    public function getPeraturan()
    {
        return $this->hasOne(Peraturan::className(), ['id' => 'id_dokumen_target']);
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
}
