<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Convenio]].
 *
 * @see Convenio
 */
class ConvenioQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Convenio[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Convenio|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
