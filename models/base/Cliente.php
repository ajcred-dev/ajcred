<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "cliente".
 *
 * @property integer $id
 * @property string $nome
 * @property string $cpf
 * @property string $situacao_cpf
 * @property string $data_nascimento
 * @property string $sexo
 * @property string $nome_mae
 * @property integer $renda
 * @property string $data_obito
 * @property string $observacao
 * @property string $novo_campo
 * @property integer $is_ativo
 * @property integer $flg_consulta_master
 * @property string $json_banco_master
 * @property integer $importado_banco_master
 * @property string $json_consiglog
 * @property integer $id_orgao_consiglog
 * @property integer $flg_consulta_consiglog
 *
 * @property \app\models\ClienteRelatorioDiferenca[] $clienteRelatorioDiferencas
 * @property \app\models\Email[] $emails
 * @property \app\models\Endereco[] $enderecos
 * @property \app\models\Matricula[] $matriculas
 * @property \app\models\Telefone[] $telefones
 */
class Cliente extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'clienteRelatorioDiferencas',
            'emails',
            'enderecos',
            'matriculas',
            'telefones'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['renda', 'is_ativo', 'flg_consulta_master', 'importado_banco_master', 'id_orgao_consiglog', 'flg_consulta_consiglog'], 'integer'],
            [['observacao', 'json_banco_master', 'json_consiglog'], 'string'],
            [['nome', 'nome_mae'], 'string', 'max' => 300],
            [['cpf'], 'string', 'max' => 15],
            [['situacao_cpf', 'data_obito'], 'string', 'max' => 45],
            [['data_nascimento', 'novo_campo'], 'string', 'max' => 150],
            [['sexo'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'cpf' => 'Cpf',
            'situacao_cpf' => 'Situacao Cpf',
            'data_nascimento' => 'Data Nascimento',
            'sexo' => 'Sexo',
            'nome_mae' => 'Nome Mae',
            'renda' => 'Renda',
            'data_obito' => 'Data Obito',
            'observacao' => 'Observacao',
            'novo_campo' => 'Novo Campo',
            'is_ativo' => 'Is Ativo',
            'flg_consulta_master' => 'Flg Consulta Master',
            'json_banco_master' => 'Json Banco Master',
            'importado_banco_master' => 'Importado Banco Master',
            'json_consiglog' => 'Json Consiglog',
            'id_orgao_consiglog' => 'Id Orgao Consiglog',
            'flg_consulta_consiglog' => 'Flg Consulta Consiglog',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClienteRelatorioDiferencas()
    {
        return $this->hasMany(\app\models\ClienteRelatorioDiferenca::className(), ['cliente_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmails()
    {
        return $this->hasMany(\app\models\Email::className(), ['cliente_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnderecos()
    {
        return $this->hasMany(\app\models\Endereco::className(), ['cliente_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatriculas()
    {
        return $this->hasMany(\app\models\Matricula::className(), ['cliente_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelefones()
    {
        return $this->hasMany(\app\models\Telefone::className(), ['cliente_id' => 'id']);
    }
    

    /**
     * @inheritdoc
     * @return \app\models\ClienteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ClienteQuery(get_called_class());
    }
}
