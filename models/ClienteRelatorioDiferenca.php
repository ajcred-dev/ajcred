<?php

namespace app\models;

use Yii;
use \app\models\base\ClienteRelatorioDiferenca as BaseClienteRelatorioDiferenca;

/**
 * This is the model class for table "cliente_relatorio_diferenca".
 */
class ClienteRelatorioDiferenca extends BaseClienteRelatorioDiferenca
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['data_inclusao'], 'safe'],
            [['cliente_id', 'convenio_id'], 'required'],
            [['cliente_id', 'convenio_id'], 'integer']
        ]);
    }
	
}
