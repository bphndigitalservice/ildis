<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Monografi]].
 *
 * @see Monografi
 */
class MonografiQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Monografi[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Monografi|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
