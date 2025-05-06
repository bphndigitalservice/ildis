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

class StockOpnameMonografi extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'stock_opname_monografi';
    }


    public function rules()
    {
        return [
            [['id_dokumen', 'tahun', 'jumlah_eksemplar', 'jumlah_scan'], 'integer'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_dokumen' => 'Id Dokumen',
            'tahun' => 'Tahun',
            'jumlah_eksemplar' => 'Jumlah Eksemplar',
            'jumlah_scan' => 'Jumlah Scan',
        ];
    }

    public function behaviors()
    {
        return [
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
