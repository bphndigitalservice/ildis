<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pcounter_users".
 *
 * @property int $id
 * @property string $user_ip
 * @property int $user_time
 * @property string|null $creation_date
 */
class PcounterUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pcounter_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_ip', 'user_time'], 'required'],
            [['user_time'], 'integer'],
            [['creation_date'], 'safe'],
            [['user_ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_ip' => 'User Ip',
            'user_time' => 'User Time',
            'creation_date' => 'Creation Date',
        ];
    }
}
