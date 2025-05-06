<?php

namespace backend\models;

use mdm\admin\components\UserStatus;
use mdm\admin\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Signup form
 */
class Signup extends Model
{
    public $username;
    public $email;
    public $password_hash;
    public $retypePassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $class = Yii::$app->getUser()->identityClass ?: 'mdm\admin\models\User';
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => $class, 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => $class, 'message' => 'This email address has already been taken.'],

            ['password_hash', 'required'],
            ['password_hash', 'string', 'min' => 6],

            ['retypePassword', 'required'],
            ['retypePassword', 'compare', 'compareAttribute' => 'password_hash'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            //$class = Yii::$app->getUser()->identityClass ?: 'mdm\admin\models\User';
            $user = new UserMember();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = 10;
            $user->setPassword($this->password_hash);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
