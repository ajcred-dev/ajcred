<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "resultado_busca".
 *
 * @property integer $id
 * @property string $margem
 * @property string $margem_disponivel
 * @property string $margem_reservada
 * @property string $margem_cartao
 * @property string $margem_cartao_reservada
 * @property string $margem_cartao_disponivel
 * @property string $margem_beneficio
 * @property string $margem_beneficio_reservada
 * @property string $margem_beneficio_disponivel
 * @property integer $busca_id
 * @property integer $matricula_id
 * @property string $data_inclusao
 *
 * @property \app\models\Contrato[] $contratos
 * @property \app\models\Busca $busca
 * @property \app\models\Matricula $matricula
 */
class ResultadoBusca extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'contratos',
            'busca',
            'matricula'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['busca_id', 'matricula_id'], 'required'],
            [['busca_id', 'matricula_id'], 'integer'],
            [['data_inclusao'], 'safe'],
            [['margem', 'margem_disponivel', 'margem_reservada', 'margem_cartao', 'margem_cartao_reservada', 'margem_cartao_disponivel', 'margem_beneficio', 'margem_beneficio_reservada', 'margem_beneficio_disponivel'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resultado_busca';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'margem' => 'Margem',
            'margem_disponivel' => 'Margem Disponivel',
            'margem_reservada' => 'Margem Reservada',
            'margem_cartao' => 'Margem Cartao',
            'margem_cartao_reservada' => 'Margem Cartao Reservada',
            'margem_cartao_disponivel' => 'Margem Cartao Disponivel',
            'margem_beneficio' => 'Margem Beneficio',
            'margem_beneficio_reservada' => 'Margem Beneficio Reservada',
            'margem_beneficio_disponivel' => 'Margem Beneficio Disponivel',
            'busca_id' => 'Busca ID',
            'matricula_id' => 'Matricula ID',
            'data_inclusao' => 'Data Inclusao',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContratos()
    {
        return $this->hasMany(\app\models\Contrato::className(), ['resultado_busca_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusca()
    {
        return $this->hasOne(\app\models\Busca::className(), ['id' => 'busca_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatricula()
    {
        return $this->hasOne(\app\models\Matricula::className(), ['id' => 'matricula_id']);
    }
    

    /**
     * @inheritdoc
     * @return \app\models\ResultadoBuscaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ResultadoBuscaQuery(get_called_class());
    }
}
