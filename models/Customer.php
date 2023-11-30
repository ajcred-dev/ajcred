<?php

namespace app\models;

use Yii;
use \app\models\base\Customer as BaseCustomer;

/**
 * This is the model class for table "customer".
 */
class Customer extends BaseCustomer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['name', 'cpf'], 'required'],
            [['createdAt', 'updatedAt', 'deletedAt'], 'safe'],
            [['name'], 'string', 'max' => 300],
            [['cpf'], 'string', 'max' => 11],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	
}
