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
        return [
            [['convenio_id', 'cliente_id'], 'required'],
            [['convenio_id', 'cliente_id'], 'integer'],
            [['matricula', 'ocupacao'], 'string', 'max' => 150],
        ];
    }
	
}
