<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "abstrak".
 *
 * @property int $id
 * @property int|null $id_dokumen
 * @property string|null $subjek ambil dari database subjek
 * @property int|null $tahun
 * @property string|null $singkatan
 * @property string|null $judul
 * @property string|null $isi_abstrak_1
 * @property string|null $isi_abstrak_2
 * @property string|null $isi_abstrak_3
 * @property string|null $catatan_1
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property string|null $catatan_2
 * @property string|null $catatan_3
 * @property string|null $catatan_4
 * @property string|null $catatan_5
 */
class Abstrak extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'abstrak';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dokumen', 'tahun', 'created_by', 'updated_by'], 'integer'],
            [['isi_abstrak_1', 'isi_abstrak_2',  'isi_abstrak_2', 'isi_abstrak_3', 'singkatan', 'judul', 'catatan_1'], 'required'],
            [['isi_abstrak_1', 'isi_abstrak_2', 'isi_abstrak_2', 'catatan_1', 'catatan_2', 'catatan_3', 'catatan_4', 'catatan_5'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['subjek', 'singkatan', 'judul'], 'string', 'max' => 255],
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
            'subjek' => 'Subjek',
            'tahun' => 'Tahun',
            'singkatan' => 'Singkatan',
            'judul' => 'Judul',
            'isi_abstrak_1' => 'Isi Abstrak 1',
            'isi_abstrak_2' => 'Isi Abstrak 2',
            'isi_abstrak_3' => 'Isi Abstrak 3',
            'catatan_1' => 'Catatan 1',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'catatan_2' => 'Catatan 2',
            'catatan_3' => 'Catatan 3',
            'catatan_4' => 'Catatan 4',
            'catatan_5' => 'Catatan 5',
        ];
    }
}
