<?php



/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\base\Cliente as BaseCliente;
use yii\console\Controller;
use yii\console\ExitCode;

use app\models\CLIENTE;
use app\models\Email;
use app\models\Endereco;
use app\models\Matricula;
use app\models\ResultadoBusca;
use app\models\Telefone;
use yii\helpers\ArrayHelper;
use yiibr\brvalidator\CpfValidator;

ini_set('memory_limit', -1);


DEFINE('CONSIGLOG_ID' , 5);


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ConsiglogController extends Controller
{   

    public function actionAtualizaGovam2(){
        $handle = fopen("csvs_importados/govam_atualizacao.csv", "r");
        $contador = $contadorMatricula = 0;

        if ($handle) {

            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $lineSplit = explode(",",$line);

                if($lineSplit < 2)continue;

                $nome = $lineSplit[0];
                $cpf = $lineSplit[1];
                $matricula = $lineSplit[2];

                $cpf = str_pad($cpf, 11, "0", STR_PAD_LEFT);

                $cliente = CLIENTE::find()->where(['cpf' => $cpf])->one();

                if($cliente){
                    
                    $matriculaEncontrada = Matricula::find()->where(['matricula' => $matricula , 'cliente_id' => $cliente->id, 'convenio_id' => 6])->one();
                    
                    //var_dump($matricula);

                    if(!$matriculaEncontrada){
                        $objMatricula = new Matricula();
                        $objMatricula->matricula = $matricula;
                        $objMatricula->cliente_id = $cliente->id;
                        $objMatricula->convenio_id = 6;
                        $objMatricula->is_ativo = 1;
                        $objMatricula->save();
                        $contadorMatricula++;
                    }

                    //var_dump($nome,$cpf,$matricula);
                    
                    #ATUALIZA NOME DO CLIENTE
                    $cliente->nome = $nome;
                    $cliente->cpf = $cpf;
                    $cliente->save();

                    echo $nome.PHP_EOL;
                    
                }else{

                    $objCliente = new CLIENTE();
                    $objCliente->nome = $nome;
                    $objCliente->cpf = $cpf;
                    $objCliente->save(false);

                    $objMatricula = new Matricula();
                    $objMatricula->matricula = $matricula;
                    $objMatricula->cliente_id = $objCliente->id;
                    $objMatricula->convenio_id = 6;
                    $objMatricula->is_ativo = 1;
                    $objMatricula->save();


                    //CADASTRAR NOVO
                    $contador++;
                }
                

                continue;

    
    
            }
            fclose($handle);
        }

        echo '[CPFS CADASTRADOS]'.$contador;
        echo '[MATRICULAS CADASTRADAS]'.$contadorMatricula;
        
        
    }


    /**ROTINA PARA SETAR MATRICULA E CPF DO GOVAM */
    public function actionAtualizaGovamFOIIIIIIIIIII(){

        //PASSO TODO MUNDO QUE ERA DO CONSIGLOG PRO GOVAM
        Matricula::updateAll(['convenio_id' => 6] , ['convenio_id' => 5]);

        //DESATIVO TODO MUNDO
        Matricula::updateAll(['is_ativo' => 0] , ['convenio_id' => 6]);

        $handle = fopen("csvs_importados/retorno-govam.txt", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $lineSplit = explode("|",$line);

                if($lineSplit < 2)continue;

                $cpf = $lineSplit[0];
                $jsonRes = json_decode($lineSplit[1],true);

    
                
                if(!empty($jsonRes) && count($jsonRes) > 1){
                    $situacao = $jsonRes[0]["situacaoNome"];
                    $matricula = $jsonRes[0]["codigoMatricula"];


                    //VOU ATIVANDO Só quem foi encontado algum dado
                    Matricula::updateAll(['is_ativo' => 1], ['matricula' => $matricula , 'convenio_id' => 6]);
                }
            }
            fclose($handle);
        } 
    }


    public function actionImportaCpfAm(){
        $handle = fopen("csvs_importados/CPF_GOV_AM.txt", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $cpf = trim($line);


                $exists = Cliente::find()->where(['cpf' => $cpf])->exists();
                

                if(!$exists){

                    $cliente = new CLIENTE();
                    $cliente->cpf = $cpf;
                    $cliente->flg_consulta_consiglog = 3;
                    $cliente->save();

                    echo $cpf.' [cpf cadastrado com sucesso]'.PHP_EOL;
                }
            }

            fclose($handle);
        }
    }
}
