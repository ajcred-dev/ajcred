<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "cliente_relatorio_diferenca".
 *
 * @property integer $id
 * @property string $data_inclusao
 * @property integer $cliente_id
 * @property integer $convenio_id
 *
 * @property \app\models\Cliente $cliente
 * @property \app\models\Convenio $convenio
 */
class ClienteRelatorioDiferenca extends \yii\db\ActiveRecord
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
            'convenio'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_inclusao'], 'safe'],
            [['cliente_id', 'convenio_id'], 'required'],
            [['cliente_id', 'convenio_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cliente_relatorio_diferenca';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data_inclusao' => 'Data Inclusao',
            'cliente_id' => 'Cliente ID',
            'convenio_id' => 'Convenio ID',
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
     * @inheritdoc
     * @return \app\models\ClienteRelatorioDiferencaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ClienteRelatorioDiferencaQuery(get_called_class());
    }
}
