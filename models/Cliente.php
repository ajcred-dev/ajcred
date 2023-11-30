<?php

namespace app\models;

use Yii;
use \app\models\base\Cliente as BaseCliente;

/**
 * This is the model class for table "cliente".
 */
class Cliente extends BaseCliente
{
    public function rules()
    {
        return [
            [['renda'], 'integer'],
            [['observacao'], 'string'],
            [['nome', 'nome_mae'], 'string', 'max' => 300],
            [['cpf'], 'string', 'max' => 15],
            [['situacao_cpf', 'data_obito'], 'string', 'max' => 45],
            [['data_nascimento', 'novo_campo'], 'string', 'max' => 150],
            [['sexo'], 'string', 'max' => 1],
        ];
    }
	
}
