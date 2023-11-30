<?php

namespace app\models;

use Yii;
use \app\models\base\Busca as BaseBusca;

/**
 * This is the model class for table "busca".
 */
class Busca extends BaseBusca
{
    public function rules()
    {
        return [
            [['dt_criacao'], 'safe'],
            [['nome'], 'string', 'max' => 150],
        ];
    }
	
}
