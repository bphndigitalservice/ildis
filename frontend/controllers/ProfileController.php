<?php


namespace frontend\controllers;

use Yii;

use backend\models\Circulation;
use backend\models\Member;
use backend\models\CirculationSearch;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use backend\web\components\FileHelper;

class ProfileController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPeminjaman()
    {
        //$model = Member::find()->where(['id' => \Yii::$app->user->identity->id])->one();
        // $searchModel = new CirculationAllSearch();

        $searchModel = new CirculationSearch(['id' => \Yii::$app->user->identity->id, 'status' => 0]);
        // $dataProvider->query->andWhere(['id'=>[2,3,4]]);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = Circulation::find()->where(['member_id' => \Yii::$app->user->identity->id, 'status' => 1])->all();
        $model2 = Circulation::find()->where(['member_id' => \Yii::$app->user->identity->id, 'status' => 0])->all();

        // Yii::$app->session->setFlash('warning', 'Data Profile sudah dibuat, anda tidak diperkenankan membuat data lain');
        //return $this->redirect(['view', 'id' => $cari->id]);
        return $this->render('index-peminjaman', ['model' => $model, 'model2' => $model2]);
    }


    public function actionProfile()
    {
        $model = Member::find()->where(['id' => \Yii::$app->user->identity->id])->one();
        // $sirkulasi = Sirkulasi::find()->where(['id' => \Yii::$app->user->identity->id, 'status' => 1])->one();
        $dataProvider = new ActiveDataProvider([
            'query' => Circulation::find()->where(['id' => \Yii::$app->user->identity->id, 'status' => 1]),
            'pagination' => ['pageSize' => 10]
        ]);
        if (!empty($model)) {
            // Yii::$app->session->setFlash('warning', 'Data Profile sudah dibuat, anda tidak diperkenankan membuat data lain');
            //return $this->redirect(['view', 'id' => $cari->id]);
            return $this->render('profile', ['model' => $model, 'dataProvider' => $dataProvider,]);
        } else {
            return $this->render('profile2');
        }
    }

    public function actionUpdate($id)
    {
        $model = Member::find()->where(['id' => \Yii::$app->user->identity->id])->one();
        $old_image = $model->member_image;

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'member_image');
            if (!empty($image)) {
                $model->member_image =  strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $image->name));
                $model->member_image = FileHelper::sanitizeFilename($image->name);
                $path = Yii::getAlias('@common') . '/dokumen/' . $model->member_image;
                $image->saveAs($path);
            }else{
                $model->member_image=$old_image;
            }

            if ($model->save()){


            Yii::$app->session->setFlash('success', 'Data Member berhasil diubah');
            return $this->redirect(['profile', 'id' => $model->id]);
        } 
        }else {
            return $this->render('update-member', [
                'model' => $model,
            ]);
        }
    }  

    public function actionPassword($id)
    {
        $model = Member::find()->where(['id' => \Yii::$app->user->identity->id])->one();
       

        if ($model->load(Yii::$app->request->post())) {
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);
        
            if ($model->save()){


            Yii::$app->session->setFlash('success', 'Password Member berhasil diubah');
            return $this->redirect(['profile', 'id' => $model->id]);
        } 
        }else {
            return $this->render('update-password', [
                'model' => $model,
            ]);
        }
    }       
}
