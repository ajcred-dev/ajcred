<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "customer".
 *
 * @property integer $id
 * @property string $name
 * @property string $cpf
 * @property string $createdAt
 * @property string $updatedAt
 * @property string $deletedAt
 *
 * @property \app\models\Employee[] $employees
 * @property \app\models\Enrollment[] $enrollments
 * @property \app\models\Phone[] $phones
 * @property \app\models\Sale[] $sales
 */
class Customer extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'employees',
            'enrollments',
            'phones',
            'sales'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'cpf'], 'required'],
            [['createdAt', 'updatedAt', 'deletedAt'], 'safe'],
            [['name'], 'string', 'max' => 300],
            [['cpf'], 'string', 'max' => 11],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     *
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock
     *
     */
    public function optimisticLock() {
        return 'lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'cpf' => 'Cpf',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'deletedAt' => 'Deleted At',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(\app\models\Employee::className(), ['fkCustomerId' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnrollments()
    {
        return $this->hasMany(\app\models\Enrollment::className(), ['fkCustomerId' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhones()
    {
        return $this->hasMany(\app\models\Phone::className(), ['fkCustomerId' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(\app\models\Sale::className(), ['fkCustomerId' => 'id']);
    }
    

    /**
     * @inheritdoc
     * @return \app\models\CustomerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\CustomerQuery(get_called_class());
    }
}
