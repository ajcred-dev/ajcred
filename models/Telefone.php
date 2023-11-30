<?php

namespace app\models;

use Yii;
use \app\models\base\Telefone as BaseTelefone;

/**
 * This is the model class for table "telefone".
 */
class Telefone extends BaseTelefone
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cliente_id'], 'required'],
            [['cliente_id', 'ranking'], 'integer'],
            [['telefone'], 'string', 'max' => 45],
            [['ddd'], 'string', 'max' => 2],
            [['is_whats'], 'string', 'max' => 1],
        ];
    }
	
}
