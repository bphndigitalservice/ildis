<?php

namespace backend\models;

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
}
