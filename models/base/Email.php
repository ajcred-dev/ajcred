<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "email".
 *
 * @property integer $id
 * @property string $email
 * @property integer $cliente_id
 *
 * @property \app\models\Cliente $cliente
 */
class Email extends \yii\db\ActiveRecord
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
            [['email'], 'string', 'max' => 150],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email';
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
            'email' => 'Email',
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
     * @return \app\models\EmailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\EmailQuery(get_called_class());
    }
}
