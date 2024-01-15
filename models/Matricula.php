<?php

namespace app\models;

use Yii;
use \app\models\base\Matricula as BaseMatricula;

/**
 * This is the model class for table "matricula".
 */
class Matricula extends BaseMatricula
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['convenio_id'], 'required'],
            [['convenio_id', 'cliente_id', 'is_ativo'], 'integer'],
            [['matricula', 'ocupacao'], 'string', 'max' => 150],
            [['codigo_convenio'], 'string', 'max' => 45],
            [['detalhe_codigo_convenio'], 'string', 'max' => 100]
        ]);
    }
	
}
