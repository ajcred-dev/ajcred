<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "endereco".
 *
 * @property integer $id
 * @property string $endereco
 * @property string $numero
 * @property string $complemento
 * @property string $bairro
 * @property string $cidade
 * @property string $uf
 * @property string $cep
 * @property integer $cliente_id
 *
 * @property \app\models\Cliente $cliente
 */
class Endereco extends \yii\db\ActiveRecord
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
            [['cliente_id'], 'integer'],
            [['endereco', 'complemento'], 'string', 'max' => 500],
            [['numero'], 'string', 'max' => 10],
            [['bairro', 'cidade'], 'string', 'max' => 150],
            [['uf'], 'string', 'max' => 2],
            [['cep'], 'string', 'max' => 15],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'endereco';
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
            'endereco' => 'Endereco',
            'numero' => 'Numero',
            'complemento' => 'Complemento',
            'bairro' => 'Bairro',
            'cidade' => 'Cidade',
            'uf' => 'Uf',
            'cep' => 'Cep',
            'cliente_id' => 'Cliente ID',
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
     * @return \app\models\EnderecoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\EnderecoQuery(get_called_class());
    }
}
