<?php

namespace backend\controllers;

use backend\models\DokumenJdih;
use backend\models\TipeDokumen;

class LaporanController extends \yii\web\Controller
{
    public function actionKoleksi()
    {
       // $peraturan = DokumenJdih::find()->all();

        $peraturan = TipeDokumen::find()->where(['parent_id'=>1])->all();
        $monografi = TipeDokumen::find()->where(['parent_id'=>2])->all();
        $artikel = TipeDokumen::find()->where(['parent_id'=>3])->all();
        $putusan = TipeDokumen::find()->where(['parent_id'=>4])->all();
        return $this->render('index-koleksi',[
            'peraturan'=>$peraturan,
            'monografi'=>$monografi,
            'artikel'=>$artikel,
            'putusan'=>$putusan
        ]);
    }

    public function actionStockopname()
    {
        return $this->render('index-stock');
    }

    public function actionMonografi()
    {
        return $this->render('index');
    }


    public function actionPeminjaman()
    {
        return $this->render('index');
    }

}
