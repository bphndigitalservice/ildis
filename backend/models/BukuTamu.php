<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "buku_tamu".
 *
 * @property int $id
 * @property string $nama_tamu
 * @property string|null $institusi_tamu
 * @property string|null $tanggal_masuk
 * @property string|null $anggota
 * @property string|null $no_telp
 * @property string|null $email
 * @property string|null $keperluan
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 */
class BukuTamu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'buku_tamu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_tamu', 'institusi_tamu', 'anggota', 'no_telp', 'email', 'keperluan'], 'required'],
            [['tanggal_masuk', 'created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'no_telp'], 'integer'],

            [['email'], 'email'],
            [['nama_tamu', 'institusi_tamu', 'anggota'], 'string', 'max' => 255],
            [['no_telp', 'email', 'keperluan'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_tamu' => 'Nama Tamu',
            'institusi_tamu' => 'Institusi Tamu',
            'tanggal_masuk' => 'Tanggal Masuk',
            'anggota' => 'Anggota',
            'no_telp' => 'No Telp',
            'email' => 'Email',
            'keperluan' => 'Keperluan',
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
            //     'attribute' => 'judul',
            //     //'immutable' => true,
            //     //'ensureUnique' => true,
            // ],
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
}
