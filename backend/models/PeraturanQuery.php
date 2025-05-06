<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Peraturan]].
 *
 * @see Peraturan
 */
class PeraturanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Peraturan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Peraturan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
