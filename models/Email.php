<?php

namespace app\models;

use Yii;
use \app\models\base\Email as BaseEmail;

/**
 * This is the model class for table "email".
 */
class Email extends BaseEmail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cliente_id'], 'required'],
            [['cliente_id'], 'integer'],
            [['email'], 'string', 'max' => 150]
        ];
    }
	
}
