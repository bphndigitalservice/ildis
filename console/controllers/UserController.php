<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;


class UserController extends Controller
{
    public function actionCreate()
    {
        $username = $this->prompt('Username:', [
            'required' => true,
        ]);

        $email = $this->prompt('Email:', [
            'required' => true,
            'validator' => function ($input, &$error) {
                if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Invalid email format.';
                    return false;
                }
                return true;
            },
        ]);

        $password = $this->prompt('Password:', [
            'required' => true,
            'mask' => '*',
            'validator' => function ($input, &$error) {
                if (strlen($input) < 6) {
                    $error = 'Password must be at least 6 characters long.';
                    return false;
                }
                return true;
            },
        ]);

        $availableRoles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
        $role = $this->select('Pilih role:', $availableRoles);

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE !== null ? User::STATUS_ACTIVE : 10;

        if ($user->save()) {
            Console::output("User '{$username}' berhasil dibuat.");

            $auth = Yii::$app->authManager;
            $roleObj = $auth->getRole($role);

            if ($roleObj) {
                $auth->assign($roleObj, $user->id);
                Console::output("Role '{$role}' berhasil diberikan.");
            } else {
                Console::error("Role '{$role}' tidak ditemukan.");
            }

            return ExitCode::OK;
        } else {
            Console::error("Gagal membuat user. Error:");
            foreach ($user->errors as $field => $errors) {
                Console::error(" - {$field}: " . implode(', ', $errors));
            }

            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}