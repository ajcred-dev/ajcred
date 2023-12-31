<?php

namespace app\controllers;

use app\models\base\Matricula;
use app\models\Busca;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Contrato;
use app\models\LoginConvenio;
use app\models\ResultadoBusca;
use yii\helpers\ArrayHelper;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ArrayDataProvider;

class ApiLoginConvenioController extends Controller
{
    
    public $enableCsrfValidation = false;


    public function actionSaveUltimaSessao($login_convenio_id){  
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        if(\Yii::$app->request->isPost){
            $loginConvenio = LoginConvenio::findOne($login_convenio_id);
            if($loginConvenio){
                $loginConvenio->ultima_sessao = $_POST['ultima_sessao'];
                $res = $loginConvenio->save();

                if($res){
                    return ['error' => false, 'retorno' => 'Sessão salva com sucesso'];
                }else{
                    return ['error' => true, 'retorno' => 'Ocorreu um erro'];
                }
            }else
            {
                return ['error' => true, 'retorno' => 'Login de Convênio não encontrado'];
            }
        }

        return ['error' => true, 'retorno' => 'Ocorreu um erro'];

        



    }

    public function actionGetCredencial($convenio='CECON'){
        
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $sql = "SELECT
                    lc.id, 
                    lc.usuario,
                    lc.senha,
                    c.sigla,
                    lc.tipo_acesso,
                    lc.descricao_tipo_acesso,
                    lc.ultima_sessao
                FROM 
                    login_convenio lc
                JOIN
                    convenio c
                ON 
                    lc.convenio_id = c.id
                WHERE
                    c.sigla ='".$convenio."' ";

        $arrLogin = \Yii::$app->db->createCommand($sql)->queryAll();

        return $arrLogin;
        
    }


}

