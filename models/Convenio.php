<?php

namespace app\models;

use Yii;
use \app\models\base\Convenio as BaseConvenio;

/**
 * This is the model class for table "convenio".
 */
class Convenio extends BaseConvenio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['nome'], 'string', 'max' => 150],
        ]);
    }
	
}
