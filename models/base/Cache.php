<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "cache".
 *
 * @property integer $id
 * @property string $nome
 * @property string $conteudo
 */
class Cache extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            ''
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['conteudo'], 'string'],
            [['nome'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cache';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'conteudo' => 'Conteudo',
        ];
    }


    /**
     * @inheritdoc
     * @return \app\models\CacheQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\CacheQuery(get_called_class());
    }
}
