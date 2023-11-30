<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "busca".
 *
 * @property integer $id
 * @property string $nome
 * @property string $dt_criacao
 * @property integer $convenio_id
 *
 * @property \app\models\Convenio $convenio
 * @property \app\models\ResultadoBusca[] $resultadoBuscas
 */
class Busca extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
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
            [['dt_criacao'], 'safe'],
            [['convenio_id'], 'required'],
            [['convenio_id'], 'integer'],
            [['nome'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'busca';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'dt_criacao' => 'Dt Criacao',
            'convenio_id' => 'Convenio ID',
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
     * @return \yii\db\ActiveQuery
     */
    public function getResultadoBuscas()
    {
        return $this->hasMany(\app\models\ResultadoBusca::className(), ['busca_id' => 'id']);
    }
    

    /**
     * @inheritdoc
     * @return \app\models\BuscaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\BuscaQuery(get_called_class());
    }
}
