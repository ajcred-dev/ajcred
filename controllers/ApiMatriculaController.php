<?php

namespace app\controllers;

use app\models\Matricula;
use app\models\Busca;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ResultadoBusca;

class ApiMatriculaController extends Controller
{

    /**
     * Caso de matricula recem importada 
     * para o banco de dados
     */
    public function actionGetListaMatriculaSemCliente($convenio_id){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $sql = "SELECT * from matricula where cliente_id IS NULL and convenio_id=".$convenio_id;

        $res = \Yii::$app->getDb()->createCommand($sql)->queryAll();

        return $res;
    }


    public function actionDesativarMatricula($matricula_id){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $matricula = Matricula::find()
            ->where(['id' => $matricula_id])
            ->one();

        if($matricula){
            $matricula->is_ativo = 0;
            $res = $matricula->save();

            if($res){
                return ["msg" => "Matricula desativada com sucesso"];
            }else{
                return ["msg" => "Erro ao desativar matricula"];
            }
        }
        return ["msg" => "Matricula não encontrada"];
    }

    /**
     * Vem a lista de matriculas para consultar
     */
    public function actionGetLista($busca_id,$convenio_id=1){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;



        $sql = "SELECT
                        m.id as id, 
                        m.matricula as matricula,
                        c.cpf,
                        c.nome
                FROM 
                    matricula as m
                JOIN
                    cliente as c
                ON
                    m.cliente_id = c.id
                WHERE 
                    m.id NOT IN (
                        SELECT 
                            rb.matricula_id 
                        FROM 
                            resultado_busca as rb 
                        WHERE 
                            busca_id = $busca_id
                        )
                AND
                    m.convenio_id = $convenio_id
                AND
                    m.is_ativo = 1";

        $res = \Yii::$app->getDb()->createCommand($sql)->queryAll();

        return $res;
    }


    
}
