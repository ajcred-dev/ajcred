<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "matricula".
 *
 * @property integer $id
 * @property integer $convenio_id
 * @property integer $cliente_id
 * @property string $matricula
 * @property string $ocupacao
 * @property integer $is_ativo
 *
 * @property \app\models\Cliente $cliente
 * @property \app\models\Convenio $convenio
 * @property \app\models\ResultadoBusca[] $resultadoBuscas
 */
class Matricula extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'cliente',
            'convenio',
            'resultadoBuscas'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['convenio_id'], 'required'],
            [['convenio_id', 'cliente_id', 'is_ativo'], 'integer'],
            [['matricula', 'ocupacao'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'matricula';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'convenio_id' => 'Convenio ID',
            'cliente_id' => 'Cliente ID',
            'matricula' => 'Matricula',
            'ocupacao' => 'Ocupacao',
            'is_ativo' => 'Is Ativo',
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
     * @return \yii\db\ActiveQuery
     */
    public function getConvenio()
    {
        return $this->hasOne(\app\models\Convenio::className(), ['id' => 'convenio_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResultadoBuscas()
    {
        return $this->hasMany(\app\models\ResultadoBusca::className(), ['matricula_id' => 'id']);
    }
    

    /**
     * @inheritdoc
     * @return \app\models\MatriculaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\MatriculaQuery(get_called_class());
    }
}
