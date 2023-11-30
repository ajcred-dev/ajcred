<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[LoginConvenio]].
 *
 * @see LoginConvenio
 */
class LoginConvenioQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return LoginConvenio[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LoginConvenio|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
