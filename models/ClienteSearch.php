<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cliente;

/**
 * app\models\ClienteSearch represents the model behind the search form about `app\models\Cliente`.
 */
 class ClienteSearch extends Cliente
{  
    public $convenio_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'renda','convenio_id'], 'integer'],
            [['nome', 'cpf', 'situacao_cpf', 'data_nascimento', 'sexo', 'nome_mae', 'data_obito', 'observacao', 'novo_campo','convenio_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        /*
            Todos que tem matricula
        */ 
        $query = Cliente::find()->select(['id','nome','cpf'])->distinct();

        #$query = Cliente::find()->distinct();
        
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        

       
    
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        if($this->convenio_id){
            $query->innerJoinWith("matriculas", true);
            $query->andWhere(['matricula.convenio_id' => $this->convenio_id]);
            $query->andWhere(['matricula.is_ativo' => 1]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'renda' => $this->renda,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'cpf', $this->cpf])
            ->andFilterWhere(['like', 'situacao_cpf', $this->situacao_cpf])
            ->andFilterWhere(['like', 'data_nascimento', $this->data_nascimento])
            ->andFilterWhere(['like', 'sexo', $this->sexo])
            ->andFilterWhere(['like', 'nome_mae', $this->nome_mae])
            ->andFilterWhere(['like', 'data_obito', $this->data_obito])
            ->andFilterWhere(['like', 'observacao', $this->observacao])
            ->andFilterWhere(['like', 'novo_campo', $this->novo_campo]);

        return $dataProvider;
    }
}
