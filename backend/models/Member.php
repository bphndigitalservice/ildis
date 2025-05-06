<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "member".
 *
 * @property int $id
 * @property string $username
 * @property string|null $password_hash
 * @property int|null $status
 * @property string $member_name
 * @property string $gender
 * @property string|null $birth_date
 * @property string $member_type_id
 * @property string|null $member_address
 * @property string $member_email
 * @property string|null $postal_code
 * @property string|null $personal_id_number
 * @property string|null $inst_name
 * @property string|null $member_image
 * @property string|null $member_ktp
 * @property string|null $member_since_date
 * @property string|null $register_date
 * @property string|null $expire_date
 * @property string|null $phone_number
 * @property string|null $fax_number
 * @property string|null $member_notes
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        // $class = Yii::$app->getUser()->identityClass ?: 'backend\models\Member';
        return [
            [['member_name', 'gender', 'member_type_id'], 'required'],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['member_email', 'filter', 'filter' => 'trim'],
            ['member_email', 'required'],
            ['member_email', 'email'],
            ['member_email', 'unique',  'message' => 'This email address has already been taken.'],

            [['status', 'created_by', 'updated_by'], 'integer'],
            [['birth_date', 'member_since_date', 'register_date', 'expire_date', 'created_at', 'updated_at'], 'safe'],
            [['member_address', 'member_image', 'member_ktp', 'member_notes'], 'string'],
            [['username', 'password_hash', 'member_name', 'gender', 'member_type_id', 'postal_code', 'personal_id_number', 'inst_name'], 'string', 'max' => 100],
            [['member_email', 'password_reset_token'], 'string', 'max' => 255],
            [['phone_number', 'fax_number'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'status' => 'Status',
            'member_name' => 'Member Name',
            'gender' => 'Gender',
            'birth_date' => 'Birth Date',
            'member_type_id' => 'Member Type ID',
            'member_address' => 'Member Address',
            'member_email' => 'Member Email',
            'postal_code' => 'Postal Code',
            'personal_id_number' => 'Personal Id Number',
            'inst_name' => 'Institusi',
            'member_image' => 'Member Image',
            'member_ktp' => 'Member KTP',
            'member_since_date' => 'Member Since Date',
            'register_date' => 'Register Date',
            'expire_date' => 'Expire Date',
            'phone_number' => 'Phone Number',
            'fax_number' => 'Fax Number',
            'member_notes' => 'Member Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
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

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getMember()
    {
        return $this->hasOne(MemberType::className(), ['id' => 'member_type_id']);
    }    
}
