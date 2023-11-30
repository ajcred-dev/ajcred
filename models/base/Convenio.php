<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "convenio".
 *
 * @property integer $id
 * @property string $nome
 * @property string $descricao
 * @property string $sigla
 *
 * @property \app\models\Busca[] $buscas
 * @property \app\models\LoginConvenio[] $loginConvenios
 * @property \app\models\Matricula[] $matriculas
 */
class Convenio extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'buscas',
            'loginConvenios',
            'matriculas'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descricao'], 'string'],
            [['nome'], 'string', 'max' => 150],
            [['sigla'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'convenio';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'descricao' => 'Descricao',
            'sigla' => 'Sigla',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuscas()
    {
        return $this->hasMany(\app\models\Busca::className(), ['convenio_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoginConvenios()
    {
        return $this->hasMany(\app\models\LoginConvenio::className(), ['convenio_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatriculas()
    {
        return $this->hasMany(\app\models\Matricula::className(), ['convenio_id' => 'id']);
    }
    

    /**
     * @inheritdoc
     * @return \app\models\ConvenioQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ConvenioQuery(get_called_class());
    }
}
