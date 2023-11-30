<?php

namespace app\models;

use Yii;
use \app\models\base\Contrato as BaseContrato;

/**
 * This is the model class for table "contrato".
 */
class Contrato extends BaseContrato
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['valor_credito', 'valor_parcela', 'valor_pagar', 'juros_anual', 'juros_mensal'], 'number'],
            [['qtd_parcela', 'resultado_busca_id', 'parcela_pagar', 'parcela_paga'], 'integer'],
            [['resultado_busca_id'], 'required'],
            [['descricao', 'nome_verba'], 'string', 'max' => 150],
            [['status_contrato'], 'string', 'max' => 1]
        ]);
    }
	
}
