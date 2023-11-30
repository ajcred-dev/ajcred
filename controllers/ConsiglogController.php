<?php

namespace app\controllers;


use app\models\Cliente;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Matricula;
use app\models\ResultadoBusca;


DEFINE('CONSIGLOG_ID',5);

class ConsiglogController extends Controller
{
    
    

    public $enableCsrfValidation = false;

    /**
     * RETORNA TODAS AS BUSCAS DISPONIVEIS
     * Padrão 1  CECON foi primeiro produto.
     */
    public function actionGetCpfNaoImportado(){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $arrBusca = Cliente::find()
            ->select(['id','cpf'])
            ->where(['flg_consulta_consiglog' => 3])
            ->all();
        return $arrBusca;
    }



    /**
     * data = {
        'orgao_id' : self.orgao,
        'cpf' : self.cpf,
        'matricula' : self.matricula,
        'nomeCliente' : self.nomeCliente,
        'json' : self.dataInfo
    }
     * 
     * 
     * 
     */
    public function actionSaveDadosBasicos($id,$nEncontrado=0){
        $cliente = Cliente::findOne(['id' => $id]);

        if($nEncontrado == 1){
            $cliente->flg_consulta_consiglog = 1;
            $cliente->save();

            exit();
        }

        if($cliente){
            $nome = $_POST['nomeCliente'];
            $idOrgaoConsiglog = $_POST['orgao_id'];
            $jsonConsiglog = $_POST['json'];
            $matricula = $_POST['matricula'];

            $cliente->nome = $nome;
            $cliente->id_orgao_consiglog = $idOrgaoConsiglog;
            $cliente->json_consiglog = $jsonConsiglog;
            
            $cliente->flg_consulta_consiglog = 1;

            $res = $cliente->save();



            var_dump($res);


            $objMatricula = Matricula::find()->where(["matricula" => $matricula , "cliente_id" => $id, "convenio_id" => CONSIGLOG_ID])->one();

            if(!$objMatricula){
                $objMatricula = new Matricula();
                $objMatricula->matricula = $matricula;
                $objMatricula->cliente_id = $cliente->id;
                $objMatricula->convenio_id = CONSIGLOG_ID;
                $res2 = $objMatricula->save();

                var_dump($res2);
            }
        }

    }



}
