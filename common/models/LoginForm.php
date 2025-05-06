<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $_user;
    public $reCaptcha;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            [['reCaptcha'], 'required'],
          
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            $failedLogins = Yii::$app->cache->get("failed_logins_{$username}") ?: 0;
            $failedLogins++;

            date_default_timezone_set('Asia/Jakarta');
            $currentTimestamp = time();

            if($user)
            {
                date_default_timezone_set('Asia/Jakarta');
                $currentTimestamp = time();
                if ($user->suspended_until && strtotime($user->suspended_until) > $currentTimestamp) {
                    $remainingTime = strtotime($user->suspended_until) - $currentTimestamp;
                    $this->addError($attribute, "Akun ditangguhkan karena salah username/password. Silakan coba lagi dalam " . ceil($remainingTime / 60) . " menit.");
                }
                else if (!$user->validatePassword($this->password)) {
                    Yii::$app->cache->set("failed_logins_{$username}", $failedLogins, 300);
                    if ($failedLogins >= 3) {
                        // Suspend the user for 5 minutes
                        $user->suspended_until = date('Y-m-d H:i:s', $currentTimestamp + 300); // 5 minutes
                        $user->save(false);

                        Yii::$app->cache->delete("failed_logins_{$username}"); // Reset attempts
                        $this->addError($attribute, 'Akun ditangguhkan selama 5 menit karena kesalahan login.');
                    } 
                    else
                        $this->addError($attribute, 'Kesalahan username atau password. Sisa '. (3 - $failedLogins) . 'x percobaan login.');
                }
            }
            else {
                Yii::$app->cache->set("failed_logins_{$username}", $failedLogins, 300);
                $this->addError($attribute, 'Kesalahan username atau password. Sisa '. (3 - $failedLogins) . 'x percobaan login.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 300 * 1 * 1 : 0);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}

