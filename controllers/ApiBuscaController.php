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
use app\models\ResultadoBusca;

class ApiBuscaController extends Controller
{
    

    /**
     * RETORNA TODAS AS BUSCAS DISPONIVEIS
     * Padrão 1  CECON foi primeiro produto.
     */
    public function actionGetAll($convenio_id=1,$limit=2){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $arrBusca = Busca::find()
            ->where(['convenio_id' => $convenio_id])
            ->orderBy('id DESC')    
            ->limit($limit)
            ->all();
        return $arrBusca;
    }


    public function actionGetUltimaBuscaDia($convenio_id=1,$limit=2){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $arrBusca = Busca::find()
            ->andWhere("dt_criacao > str_to_date('".date('Y-m-d 00:00:00')."','%Y-%m-%d %H:%i:%s')")
            ->andWhere(["convenio_id" => $convenio_id])
            ->orderBy('id DESC')    
            ->one();
            
        return $arrBusca;
    }
    
}
