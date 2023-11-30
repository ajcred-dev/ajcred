<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "usuario".
 *
 * @property integer $id
 * @property string $email
 * @property string $senha
 * @property string $ultimo_acesso
 * @property integer $status_cadastro
 *
 * @property \app\models\AuthAssignment[] $authAssignments
 */
class Usuario extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'authAssignments'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'senha'], 'required'],
            [['ultimo_acesso'], 'safe'],
            [['status_cadastro'], 'integer'],
            [['email', 'senha'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'senha' => 'Senha',
            'ultimo_acesso' => 'Ultimo Acesso',
            'status_cadastro' => 'Status Cadastro',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(\app\models\AuthAssignment::className(), ['user_id' => 'id']);
    }
    

    /**
     * @inheritdoc
     * @return \app\models\UsuarioQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\UsuarioQuery(get_called_class());
    }
}
