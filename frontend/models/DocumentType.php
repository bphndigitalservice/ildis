<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "document_type".
 *
 * @property int $id
 * @property string $second_id
 * @property int $parent_id
 * @property string $name
 * @property string $singkatan
 * @property string|null $status
 * @property int|null $integrasi
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class DocumentType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['second_id', 'parent_id', 'name', 'singkatan'], 'required'],
            [['parent_id', 'integrasi', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['second_id', 'name', 'singkatan', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'second_id' => 'Second ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'singkatan' => 'Singkatan',
            'status' => 'Status',
            'integrasi' => 'Integrasi',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
