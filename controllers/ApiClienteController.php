<?php

namespace app\controllers;

use app\models\base\Convenio;
use app\models\Matricula;
use app\models\Busca;
use app\models\Cliente;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ResultadoBusca;
use SebastianBergmann\Type\FalseType;

class ApiClienteController extends Controller
{


    public $enableCsrfValidation = false;


    public function actionSaveCliente()
    {

        $objCliente = new Cliente();
        
        /**
         * CAMPOS OBRIGATORIOS
         * 
         */
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];


        $objCliente->nome = $nome;
        $objCliente->cpf = $cpf ;
        

        if(isset($_POST['orgao_id'])){
            $orgao_id = $_POST['orgao_id'];
            $objCliente->id_orgao_consiglog = $orgao_id;
        }

        if(isset($_POST['json_consiglog'])){
            $jsonConsiglog = $_POST['json_consiglog'];
            $objCliente->json_consiglog = $jsonConsiglog;
        }

        $objCliente->save();

        /**
         * Se tiver setado matricula e orgao_id
         */
        if(isset($_POST['matricula']) && isset($_POST['convenio_id'])){
            
            $matricula = $_POST['matricula'];
            $convenio_id = $_POST['convenio_id'];

            $objMatricula = new Matricula();
            $objMatricula->matricula = $matricula;
            $objMatricula->convenio_id = $convenio_id;
            $objMatricula->cliente_id = $objCliente->id;
            $objMatricula->save();
            

        }

    }


    public function actionSaveDados(){
        $json = $_POST['json'];

        $jsonDecode = json_decode($json,true);
        
        $cpf = trim($jsonDecode[0]['numCpf']);

        $objCliente = Cliente::findOne(['cpf' => $cpf]);
        
        
        /**
         * 
         * Não tenho o cliente com o cpf cadastrado salvo
         * 
         */
        if(!$objCliente){
            $objCliente = new Cliente();
            $objCliente->nome = $jsonDecode[0]['nomFuncionario'];
            $objCliente->cpf = $cpf;
            $res = $objCliente->save();
        }

            

        if($objCliente){
            
            /*
                Vejo se a matricula está vinculada a um cpf
            */

            $objMatricula = \app\models\Matricula::findOne(["matricula" => (string) $jsonDecode[0]['numMatricula']]);
            
            

            if($objMatricula){
                $objMatricula->cliente_id = $objCliente->id;
                $objMatricula->ocupacao = $jsonDecode[0]['nomClassificacao'];
                $res = $objMatricula->save();
                

            }
        }
        
    }

    /**
     * Retorna todos os clientes do banco que ainda 
     * não fizeram consulta no banco master
     * 
     * FLG_CONSULTA_MASTER => 3 Ainda falta ser testado
     */
    public function actionGetAllClienteMaster(){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $arrClienteMaster = Cliente::find()
                        ->select(['cpf'])
                        ->where(['flg_consulta_master'  => 3])
                        ->orderBy('id DESC')
                        ->all();

        return $arrClienteMaster;
    }



    public function actionSaveClienteMaster(){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $cpf = $_POST['cpf'];
        $dados  =  $_POST['dados'];

        $objCliente = Cliente::findOne(['cpf' => $cpf]);
        $objCliente->json_banco_master = $dados;
        $objCliente->flg_consulta_master = 1;
        $objCliente->save();

        return $objCliente->id;
    }



    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionCreateClienteByCpf($cpf)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $qtdCliente = Cliente::find()->where(['cpf' => $cpf])->count();

        $qtdCliente = (int)$qtdCliente;

        if($qtdCliente > 0){
            $data = [
                "error" => true,
                "msg" => "CPF já consta na base de dados"
            ];
            return $data;
        }

        
        $cliente = new Cliente();
        $cliente->cpf = $cpf;
        $res = $cliente->save();

        if($res){
            $data = [
                "error" => false,
                "msg" => "CPF incluído com sucesso!"
            ];
            
            return $data;
        }
    }

    
    public function actionDesativaMatriculaByCpfAndMatricula($cpf,$matricula){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $objMatricula = Matricula::find()
                            ->joinWith(['cliente'])
                            ->andWhere(['cliente.cpf' => $cpf])
                            ->andWhere(['matricula.matricula' => $matricula])
                            ->one();

        var_dump($objMatricula);
        exit;
    }


}
