<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "login_convenio".
 *
 * @property integer $id
 * @property string $usuario
 * @property string $senha
 * @property integer $tipo_acesso
 * @property integer $convenio_id
 * @property string $descricao_tipo_acesso
 * @property string $ultima_sessao
 *
 * @property \app\models\Convenio $convenio
 */
class LoginConvenio extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'convenio'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo_acesso', 'convenio_id'], 'integer'],
            [['convenio_id'], 'required'],
            [['ultima_sessao'], 'string'],
            [['usuario', 'senha'], 'string', 'max' => 150],
            [['descricao_tipo_acesso'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'login_convenio';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario' => 'Usuario',
            'senha' => 'Senha',
            'tipo_acesso' => 'Tipo Acesso',
            'convenio_id' => 'Convenio ID',
            'descricao_tipo_acesso' => 'Descricao Tipo Acesso',
            'ultima_sessao' => 'Ultima Sessao',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConvenio()
    {
        return $this->hasOne(\app\models\Convenio::className(), ['id' => 'convenio_id']);
    }
    

    /**
     * @inheritdoc
     * @return \app\models\LoginConvenioQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\LoginConvenioQuery(get_called_class());
    }
}
