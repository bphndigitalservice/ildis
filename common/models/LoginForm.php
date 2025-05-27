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
        if ($this->hasErrors()) {
            return;
        }

        $user = $this->getUser();
        $username = $this->username;
        $cacheKey = "failed_logins_{$username}";
        $failedLogins = Yii::$app->cache->get($cacheKey) ?: 0;

        date_default_timezone_set('Asia/Jakarta');
        $now = time();

        if ($user) {

            if ($user->suspended_until && strtotime($user->suspended_until) > $now) {
                $remaining = strtotime($user->suspended_until) - $now;
                $minutesLeft = ceil($remaining / 60);
                $this->addError($attribute, "Akun ditangguhkan. Coba lagi dalam {$minutesLeft} menit.");
                return;
            }


            if (!$user->validatePassword($this->password)) {
                $failedLogins++;
                Yii::$app->cache->set($cacheKey, $failedLogins, 300);

                if ($failedLogins >= 3) {
                    // Suspend akun
                    $user->suspended_until = date('Y-m-d H:i:s', $now + 300);
                    $user->save(false);
                    Yii::$app->cache->delete($cacheKey);
                    $this->addError($attribute, "Akun ditangguhkan selama 5 menit karena salah login 3x berturut-turut.");
                } else {
                    $this->addError($attribute, "Kesalahan username atau password. Sisa percobaan login: " . (3 - $failedLogins));
                }
            }
        } else {
            // Username tidak ditemukan
            $failedLogins++;
            Yii::$app->cache->set($cacheKey, $failedLogins, 300);
            $this->addError($attribute, "Kesalahan username atau password. Sisa percobaan login: " . (3 - $failedLogins));
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

