<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Cliente]].
 *
 * @see Cliente
 */
class ClienteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Cliente[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Cliente|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
