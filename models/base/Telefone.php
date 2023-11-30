<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "telefone".
 *
 * @property integer $id
 * @property string $telefone
 * @property string $ddd
 * @property integer $cliente_id
 * @property integer $ranking
 * @property string $is_whats
 *
 * @property \app\models\Cliente $cliente
 */
class Telefone extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'cliente'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cliente_id'], 'required'],
            [['cliente_id', 'ranking'], 'integer'],
            [['telefone'], 'string', 'max' => 45],
            [['ddd'], 'string', 'max' => 2],
            [['is_whats'], 'string', 'max' => 1],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telefone';
    }

    /**
     *
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock
     *
     */
    public function optimisticLock() {
        return 'lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'telefone' => 'Telefone',
            'ddd' => 'Ddd',
            'cliente_id' => 'Cliente ID',
            'ranking' => 'Ranking',
            'is_whats' => 'Is Whats',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(\app\models\Cliente::className(), ['id' => 'cliente_id']);
    }
    

    /**
     * @inheritdoc
     * @return \app\models\TelefoneQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\TelefoneQuery(get_called_class());
    }
}
