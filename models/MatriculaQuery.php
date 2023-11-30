<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Matricula]].
 *
 * @see Matricula
 */
class MatriculaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Matricula[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Matricula|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
