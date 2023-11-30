<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "contrato".
 *
 * @property integer $id
 * @property string $descricao
 * @property double $valor_credito
 * @property double $valor_parcela
 * @property integer $qtd_parcela
 * @property double $valor_pagar
 * @property string $nome_verba
 * @property integer $resultado_busca_id
 * @property integer $parcela_pagar
 * @property integer $parcela_paga
 * @property string $status_contrato
 * @property double $juros_anual
 * @property double $juros_mensal
 *
 * @property \app\models\ResultadoBusca $resultadoBusca
 */
class Contrato extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'resultadoBusca'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['valor_credito', 'valor_parcela', 'valor_pagar', 'juros_anual', 'juros_mensal'], 'number'],
            [['qtd_parcela', 'resultado_busca_id', 'parcela_pagar', 'parcela_paga'], 'integer'],
            [['resultado_busca_id'], 'required'],
            [['descricao', 'nome_verba'], 'string', 'max' => 150],
            [['status_contrato'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contrato';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descricao' => 'Descricao',
            'valor_credito' => 'Valor Credito',
            'valor_parcela' => 'Valor Parcela',
            'qtd_parcela' => 'Qtd Parcela',
            'valor_pagar' => 'Valor Pagar',
            'nome_verba' => 'Nome Verba',
            'resultado_busca_id' => 'Resultado Busca ID',
            'parcela_pagar' => 'Parcela Pagar',
            'parcela_paga' => 'Parcela Paga',
            'status_contrato' => 'Status Contrato',
            'juros_anual' => 'Juros Anual',
            'juros_mensal' => 'Juros Mensal',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResultadoBusca()
    {
        return $this->hasOne(\app\models\ResultadoBusca::className(), ['id' => 'resultado_busca_id']);
    }
    

    /**
     * @inheritdoc
     * @return \app\models\ContratoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ContratoQuery(get_called_class());
    }
}
