<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

class StockOpnameEksemplar extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'stock_opname_eksemplar';
    }


    public function rules()
    {
        return [
            [['kode_eksemplar', 'tanggal', 'dokumen_id', 'tahun'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['dokumen_id', 'tahun', 'created_by', 'updated_by'], 'integer'],
            [['kode_eksemplar'], 'string', 'max' => 100],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_eksemplar' => 'Kode Eksemplar',
            'tanggal' => 'Tanggal',
            'dokumen_id' => 'Dokumen ID',
            'tahun' => 'Tahun',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function behaviors()
    {
        return [
            // [
            //     'class' => SluggableBehavior::className(),
            //     'attribute' => 'slug',
            //     'immutable' => true,
            //     'ensureUnique' => true,
            // ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }




    public function getDokumen()
    {
        return $this->hasOne(DokumenJdih::class, ['id' => 'dokumen_id']);
    }

    



    public function getUserInput($id)
    {
        $user = User::findOne($id);
        if (!empty($user)) {
            return $user->username;
        } else {
            return '';
        }
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

    public function getTanggal($tanggal) // fungsi atau method untuk mengubah hari, tanggal ke format indonesia
    {
        $BulanIndo = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $HariIndo = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $sepparator = '-';
        $parts = explode($sepparator, $tanggal);

        //$hari = $HariIndo[date("w", mktime(0, 0, 0, $parts[1], $parts[2], $parts[0]))]; //mendapatkan hari indonesia
        $tgl = substr($tanggal, 8, 2); // memisahkan format tanggal menggunakan substring
        $bulan = substr($tanggal, 5, 2); // memisahkan format bulan menggunakan substring
        $tahun = substr($tanggal, 0, 4); // memisahkan format tahun menggunakan substring

        //$result = $hari .", " .$tgl . " " . $BulanIndo[(int)$bulan] . " ". $tahun;
        $result = $tgl . " " . $BulanIndo[(int)$bulan] . " " . $tahun;
        return ($result);
    }


    public function getTanggal2($tanggal) // fungsi atau method untuk mengubah hari, tanggal ke format indonesia
    {
        $BulanIndo = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $HariIndo = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $sepparator = '-';
        $parts = explode($sepparator, $tanggal);

        $sepparator2 = ' ';
        $parts2 = explode($sepparator2, $tanggal);

        $tgl = substr($tanggal, 8, 2); // memisahkan format tanggal menggunakan substring
        $bulan = substr($tanggal, 5, 2); // memisahkan format bulan menggunakan substring
        $tahun = substr($tanggal, 0, 4); // memisahkan format tahun menggunakan substring

        $result = $tgl . " " . $BulanIndo[(int)$bulan] . " " . $tahun;
        return $result . ' Pukul ' . $parts2[1];
    }
}
