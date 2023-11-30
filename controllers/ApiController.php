<?php

namespace app\controllers;

use app\models\base\Convenio;
use app\models\base\Matricula;
use app\models\Busca;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ResultadoBusca;

class ApiController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionCreateBusca($produto)
    {

        $objConvenio = Convenio::findOne(['sigla' => $produto]);

        $this->layout = False;

        $nomeProduto = $produto."_".md5(microtime(true));

        $busca = new Busca();
        $busca->nome = $nomeProduto;
        $busca->convenio_id = $objConvenio->id;
        
        $busca->save();

        echo json_encode(["id" => $busca->id , "nome" => $busca->nome]);
        exit;
    }


    public function actionGetMatriculaId($matricula,$convenio){
        $model   = Matricula::find()
           ->select('id')
           ->where(['matricula' => $matricula, 'convenio_id' => $convenio])
           ->one();
        
           echo json_encode(["id" => $model->id ]);
           exit;
    }


    public function actionGetResultadoBusca($busca_id){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $arrResultadoBusca = ResultadoBusca::find()->all();
        return $arrResultadoBusca;
    }


    public function actionSaveResultadoBusca($busca_id,$matricula_id,$margem,$margem_cartao){
        $resultadoBusca = new ResultadoBusca();
        $resultadoBusca->busca_id = $busca_id;
        $resultadoBusca->matricula_id = $matricula_id;
        $resultadoBusca->margem = $margem;
        $resultadoBusca->margem_cartao = $margem_cartao;

        $resultadoBusca->save();

        echo json_encode(["id" => $resultadoBusca->id]);
        exit;

    }

    
}
