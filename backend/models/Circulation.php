<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "circulation".
 *
 * @property int $id
 * @property int $member_id
 * @property string $member
 * @property int $document_id
 * @property string|null $title
 * @property int|null $item_id
 * @property string|null $item_code
 * @property string $tanggal_pinjam
 * @property string $tanggal_kembali
 * @property string $status
 * @property string $denda
 * @property int|null $_created_by
 * @property int|null $_updated_by
 * @property string $_created_time
 * @property string $_updated_time
 */
class Circulation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'circulation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'document_id'], 'required'],
            [['member_id', 'document_id', 'item_id', 'created_by', 'updated_by', 'status'], 'integer'],
            [['tanggal_pinjam', 'tanggal_kembali', 'created_at', 'updated_at'], 'safe'],
            [['member', 'title', 'item_code',  'denda','status_peminjaman'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'member' => 'Member',
            'document_id' => 'Document ID',
            'title' => 'Title',
            'item_id' => 'Item ID',
            'item_code' => 'Item Code',
            'tanggal_pinjam' => 'Tanggal Pinjam',
            'tanggal_kembali' => 'Tanggal Kembali',
            'status' => 'Status',
            'status_peminjaman'=>'Status Peminjaman',
            'denda' => 'Denda',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
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


    public function getJudul($id)
    {
        $user = Monografi::findOne($id);
        return $user->judul;
    }



    public function getPinjam($id)
    {
        switch($id){
            case 'Dipinjam' :
            return '<span class="label label-danger">'.$id.'</span>';
            break;

            case 'Telah Kembali' :
            return '<span class="label label-success">'.$id.'</span>';
            break;

        }
    }


    public function getDenda($id,$id2)
    {

      $model = Member::findOne($id2);
      $member = MemberType::findOne($model->member_type_id);

      $denda = '';
      $timeStart = strtotime($id);
      $timeEnd = strtotime(date('Y-m-d'));

      $terlambat = date("d", $timeEnd) - date("d", $timeStart);

      if ($terlambat>0){
        $denda = $terlambat * $member->fine_each_day;

        return 'Rp.  ' . number_format($denda, 0, ',', '.');
    }else{
        return '';
    }

    // if ($numBulan > $member->loan_limit) {
    //     $numBulan=$numBulan-$member->loan_limit;
    //     $denda = $numBulan * $member->fine_each_day;
    // }

        // $kembali = date('Y-m-d');

        // $durasi = $kembali -  $id;
        // return $numBulan;
    
    // $total = number_format($denda);
    // return $total;
    // if(empty($denda)){
    //     return $denda;
    // }else{
    //     return 'Rp.  ' . number_format($denda, 0, ',', '.');
    // }

}
// public function getTerlambat($id,$id2,$id3)
// {
//   $model = Member::findOne($id3);
//   $member = MemberType::findOne($model->member_type_id);
//   //var_dump($member->loan_periode);
//   $timeStart = strtotime($id2);
//   $timeEnd = strtotime(date('Y-m-d'));

//   $terlambat = date("d", $timeEnd) - date("d", $timeStart);
//   echo  $terlambat;
//   if ($terlambat>$member->loan_periode){
//     $terlambat=$terlambat-$member->loan_periode;
//    // return $terlambat;
// }else{
//     return '';
// }
// }

public function getTerlambat($id,$id2)
{
    $timeStart = strtotime($id2);
    $timeEnd = strtotime(date('Y-m-d'));
    $terlambat = date("d", $timeEnd) - date("d", $timeStart);

    if ($terlambat>0){
        return $terlambat;
    }else{
        return '';
    }
    return $terlambat;    
    if ($terlambat>3){
        $terlambat=$terlambat-3;
        return $terlambat;
    }else{
        return '';
    }
}

}
