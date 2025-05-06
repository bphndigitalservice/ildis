<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[MasukanMasyarakat]].
 *
 * @see MasukanMasyarakat
 */
class MasukanMasyarakatQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MasukanMasyarakat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MasukanMasyarakat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
