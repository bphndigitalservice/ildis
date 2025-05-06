<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Rancangan]].
 *
 * @see Rancangan
 */
class RancanganQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Rancangan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Rancangan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

     public function total($id)
     {
        return $this->andWhere(['tahapan_rancangan' => $id])->count();
     } 

     public function jenis($id)
     {
        return $this->andWhere(['tahapan_rancangan' => $id])->limit(4)->orderBy('tanggal_akhir_publish DESC')->all();
     }      
     
}
