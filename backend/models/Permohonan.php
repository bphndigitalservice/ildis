<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "permohonan".
 *
 * @property string $id
 * @property string|null $nomor Nomor Tanda Terima
 * @property int $jenis_id Jenis Permohonan
 * @property int $divisi_id Unit Bawaslu
 * @property int $kualifikasi_id Jenis Kualifikasi
 * @property int $kualifikasi_pemohon_id Kualifikasi Pemohon
 * @property string|null $tanggal_permohonan Tanggal Permohonan
 * @property string $nomor_sengketa Nomor Objek Sengketa
 * @property string $perihal_sengketa Perihal Objek Sengketa
 * @property string $tanggal_sengketa Tanggal Objek Sengketa
 * @property int $unit_id Nama Instansi
 * @property string|null $provinsi_id Provinsi
 * @property string|null $kab_kota_id Kabupaten/Kota
 * @property string|null $tahun
 * @property string|null $created_at Tanggal dibuat
 * @property int|null $created_by Dibuat Oleh
 * @property string|null $updated_at Tanggal diupdate
 * @property int|null $updated_by Diupdate Oleh
 * @property int $termohon_id
 * @property string|null $status_permohonan
 * @property int|null $status_register
 * @property string|null $alasan
 *
 * @property Pemohon[] $pemohons
 */
class Permohonan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permohonan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'jenis_id', 'divisi_id', 'kualifikasi_id', 'kualifikasi_pemohon_id', 'nomor_sengketa', 'perihal_sengketa', 'tanggal_sengketa', 'unit_id', 'termohon_id'], 'required'],
            [['jenis_id', 'divisi_id', 'kualifikasi_id', 'kualifikasi_pemohon_id', 'unit_id', 'created_by', 'updated_by', 'termohon_id', 'status_register'], 'integer'],
            [['tanggal_permohonan', 'tanggal_sengketa', 'created_at', 'updated_at'], 'safe'],
            [['perihal_sengketa'], 'string'],
            [['id'], 'string', 'max' => 32],
            [['nomor', 'nomor_sengketa'], 'string', 'max' => 100],
            [['provinsi_id', 'kab_kota_id'], 'string', 'max' => 20],
            [['tahun'], 'string', 'max' => 4],
            [['status_permohonan', 'alasan'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomor' => 'Nomor',
            'jenis_id' => 'Jenis ID',
            'divisi_id' => 'Divisi ID',
            'kualifikasi_id' => 'Kualifikasi ID',
            'kualifikasi_pemohon_id' => 'Kualifikasi Pemohon ID',
            'tanggal_permohonan' => 'Tanggal Permohonan',
            'nomor_sengketa' => 'Nomor Sengketa',
            'perihal_sengketa' => 'Perihal Sengketa',
            'tanggal_sengketa' => 'Tanggal Sengketa',
            'unit_id' => 'Unit ID',
            'provinsi_id' => 'Provinsi ID',
            'kab_kota_id' => 'Kab Kota ID',
            'tahun' => 'Tahun',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'termohon_id' => 'Termohon ID',
            'status_permohonan' => 'Status Permohonan',
            'status_register' => 'Status Register',
            'alasan' => 'Alasan',
        ];
    }

    /**
     * Gets query for [[Pemohons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPemohons()
    {
        return $this->hasMany(Pemohon::className(), ['permohonan_id' => 'id']);
    }
}
