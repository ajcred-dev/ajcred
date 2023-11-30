<?php

namespace app\models;

use Yii;
use \app\models\base\Cache as BaseCache;

/**
 * This is the model class for table "cache".
 */
class Cache extends BaseCache
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['conteudo'], 'string'],
            [['nome'], 'string', 'max' => 150]
        ]);
    }
	
}
