<?php

namespace app\models;

use Yii;
use \app\models\base\ResultadoBusca as BaseResultadoBusca;

/**
 * This is the model class for table "resultado_busca".
 */
class ResultadoBusca extends BaseResultadoBusca
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['busca_id', 'matricula_id'], 'required'],
            [['busca_id', 'matricula_id'], 'integer'],
            [['margem', 'margem_cartao'], 'string', 'max' => 150],
        ];
    }
	
}
