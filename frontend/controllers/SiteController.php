<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\MemberForm;
use frontend\models\ContactForm;


class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'profile'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {

        $berita = \frontend\models\Berita::find()->where(['status' => 1])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            //->offset(1)
            ->all();
        
        return $this->render('index', [
            'berita' => $berita
        ]);
    }


    public function actionSekilasSejarah()
    {
        return $this->render('sekilas-sejarah');
    }

    public function actionDasarHukum()
    {
        return $this->render('dasar-hukum');
    }

    public function actionVisi()
    {
        return $this->render('visi');
    }

    public function actionMisi()
    {
        return $this->render('misi');
    }

    public function actionSto()
    {
        return $this->render('struktur-organisasi');
    }

     public function actionStoinstansi()
    {
        return $this->render('struktur-organisasi-jdihinstansi');
    }

    public function actionSop()
    {
        return $this->render('sop');
    }

    public function actionModalikm()
    {
        return $this->render('modal');
    }

     public function actionVisitor()
    {
        return $this->render('visitor');
    }

    public function actionPengelola()
    {
        return $this->render('pengelola');
    }



    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new MemberForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', 'Selamat Datang Di Website JDIH');
            //return $this->goBack();
            return $this->render('/profile/index', ['model' => $model]);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('warning', 'anda telah logout dari web aplikasi kami');
        return $this->goHome();
    }

    public function actionKontaks()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('kontak', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionKontak()
    {
        return $this->render('kontak');
    }
}
