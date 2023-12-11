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
        return array_replace_recursive(parent::rules(),
	    [
            [['busca_id', 'matricula_id'], 'required'],
            [['busca_id', 'matricula_id'], 'integer'],
            [['data_inclusao'], 'safe'],
            [['margem', 'margem_disponivel', 'margem_reservada', 'margem_cartao', 'margem_cartao_reservada', 'margem_cartao_disponivel', 'margem_beneficio', 'margem_beneficio_reservada', 'margem_beneficio_disponivel', 'margem_sindicato', 'margem_sindicato_reservada', 'margem_sindicato_disponivel'], 'string', 'max' => 150]
        ]);
    }
	
}
