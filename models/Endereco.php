<?php

namespace app\models;

use Yii;
use \app\models\base\Endereco as BaseEndereco;

/**
 * This is the model class for table "endereco".
 */
class Endereco extends BaseEndereco
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cliente_id'], 'required'],
            [['cliente_id'], 'integer'],
            [['endereco', 'complemento'], 'string', 'max' => 500],
            [['numero'], 'string', 'max' => 10],
            [['bairro', 'cidade'], 'string', 'max' => 150],
            [['uf'], 'string', 'max' => 2],
            [['cep'], 'string', 'max' => 15]
        ];
    }
	
}
