<?php

namespace app\models;

use Yii;
use \app\models\base\LoginConvenio as BaseLoginConvenio;

/**
 * This is the model class for table "login_convenio".
 */
class LoginConvenio extends BaseLoginConvenio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['tipo_acesso', 'convenio_id'], 'integer'],
            [['convenio_id'], 'required'],
            [['ultima_sessao'], 'string'],
            [['usuario', 'senha'], 'string', 'max' => 150],
            [['descricao_tipo_acesso'], 'string', 'max' => 250]
        ]);
    }
	
}
