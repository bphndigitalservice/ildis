<?php

namespace backend\modules\admin\controllers;

use backend\models\LogPustakawan;
use backend\models\Users;
use backend\web\components\FileHelper;
use mdm\admin\components\UserStatus;
use mdm\admin\controllers\UserController as BaseUserController;
use mdm\admin\models\form\ChangePassword;
use mdm\admin\models\form\PasswordResetRequest;
use mdm\admin\models\form\ResetPassword;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


/**
 * UserController handles the CRUD actions for User model.
 */
class UserController extends BaseUserController
{

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $changePassword = new ChangePassword();
        if ($changePassword->load(\Yii::$app->request->post()) && $changePassword->change()) {
            \Yii::$app->session->setFlash('success', 'Password changed successfully.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $log = new ActiveDataProvider([
            'query' => \backend\models\LogPustakawan::find()->where(['created_by' => \Yii::$app->user->identity->id]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'log' => $log,
            'changePassword' => $changePassword,
        ]);
    }


    /**
     * Updates a user profile.
     * @param integer $id
     * @return mixed
     */
    public function actionProfile($id)
    {
        $model = Users::findOne(\Yii::$app->user->identity->id);

        if ($model->load(\Yii::$app->getRequest()->post())) {

            $picture = UploadedFile::getInstance($model, 'picture');
            if (!empty($picture)) {
                $model->picture = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $picture->name));
                $model->picture = FileHelper::sanitizeFilename($picture->name);
                $path = \Yii::getAlias('@common') . '/dokumen/' . $model->picture;
                $picture->saveAs($path);
            }
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'picture successfully changed');
                return $this->redirect(['profile', 'id' => $id]);
            }
        }


        if (\Yii::$app->user->identity->id != $id) {
            \Yii::$app->session->setFlash('warning', 'anda tidak diizinkan melihat profile user lain');
            return $this->redirect(['profile', 'id' => \Yii::$app->user->identity->id]);
        }

        $changePassword = new ChangePassword();
        if ($changePassword->load(\Yii::$app->getRequest()->post()) && $changePassword->change()) {
            \Yii::$app->session->setFlash('success', 'ganti password berhasi, silahkan login dengan password yang baru');
            return $this->redirect(['profile', 'id' => $id]);
        }

        $log = new ActiveDataProvider([
            'query' => LogPustakawan::find()->where(['created_by' => \Yii::$app->user->identity->id]),
            'pagination' => ['pageSize' => 10]
        ]);


        return $this->render('view', [
            'model' => $model,
            'changePassword' => $changePassword,
            'log' => $log,
        ]);
    }


    /**
     * Inactivate a user.
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionInactivate($id)
    {
        return $this->updateUserStatus($id, UserStatus::INACTIVE, 'inactive');
    }

    /**
     * Deactivate a user.
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionActivate($id)
    {
        return $this->updateUserStatus($id, UserStatus::ACTIVE, 'active');
    }


    /**
     * Updates the status of a user.
     * @param integer $id
     * @param integer $newStatus
     * @param string $label
     * @return \yii\web\Response
     */
    protected function updateUserStatus($id, $newStatus, $label)
    {
        $user = $this->findModel($id);
        if ($user->status == $newStatus) {
            \Yii::$app->session->setFlash('warning', "User is already {$label}.");
        } else {
            $user->status = $newStatus;
            if ($user->save()) {
                \Yii::$app->session->setFlash('success', "User status changed to {$label}.");
            } else {
                \Yii::$app->session->setFlash('error', 'Failed to change user status.');
            }
        }

        return $this->goHome();
    }



    /**
        * Request password reset
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\web\ForbiddenHttpException
     *
     * Request reset password
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequest();
        if ($model->load(\Yii::$app->getRequest()->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                \Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                \Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Reset password
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPassword($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->validate() && $model->resetPassword()) {
            \Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Reset password
     */
    public function actionChangePassword()
    {
        $model = new ChangePassword();
        if ($model->load(\Yii::$app->getRequest()->post()) && $model->change()) {
            return $this->goHome();
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

}