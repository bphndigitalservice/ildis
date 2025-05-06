<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "member_type".
 *
 * @property int $id
 * @property string $member_type_name Nama tipe Member
 * @property int $loan_limit jumlah_maksimal_peminjaman
 * @property int $loan_periode lama_maksimal_peminjaman
 * @property int|null $enable_reserve status aktif/tidak
 * @property int|null $reserve_limit jumlah_maksimal_reservasi
 * @property int|null $member_periode masa_berlaku_member
 * @property int|null $reborrow_limit maksimal perpanjangan
 * @property int|null $fine_each_day denda_perhari
 * @property int|null $grace_periode toleransi_keterlambatan
 * @property string|null $input_date
 * @property string|null $last_update
 * @property string|null $id_tipe_koleksi
 * @property string|null $id_tipe_gmd
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class MemberType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_type_name', 'loan_limit', 'loan_periode','fine_each_day'], 'required'],
            [['loan_limit', 'loan_periode', 'enable_reserve', 'reserve_limit', 'member_periode', 'reborrow_limit', 'fine_each_day', 'grace_periode', 'created_by', 'updated_by'], 'integer'],
            [['input_date', 'last_update', 'created_at', 'updated_at'], 'safe'],
            [['member_type_name'], 'string', 'max' => 50],
            [['id_tipe_koleksi', 'id_tipe_gmd'], 'string', 'max' => 255],
            [['member_type_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_type_name' => 'Type Member',
            'loan_limit' => 'Jumlah Peminjaman',
            'loan_periode' => 'Lama Peminjaman',
            'enable_reserve' => 'status aktif/tidak',
            'reserve_limit' => 'jumlah_maksimal_reservasi',
            'member_periode' => 'masa_berlaku_member',
            'reborrow_limit' => 'maksimal perpanjangan',
            'fine_each_day' => 'Denda Perhari',
            'grace_periode' => 'toleransi_keterlambatan',
            'input_date' => 'Input Date',
            'last_update' => 'Last Update',
            'id_tipe_koleksi' => 'Id Tipe Koleksi',
            'id_tipe_gmd' => 'Id Tipe Gmd',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
