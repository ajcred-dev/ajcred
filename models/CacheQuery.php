<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Cache]].
 *
 * @see Cache
 */
class CacheQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Cache[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Cache|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
