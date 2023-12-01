<?php

namespace app\controllers;

use app\models\base\Cliente;
use app\models\base\Matricula;
use app\models\Busca;
use app\models\Cache;
use app\models\Cliente as ModelsCliente;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Contrato;
use app\models\Convenio;
use app\models\ResultadoBusca;
use yii\helpers\ArrayHelper;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ArrayDataProvider;


ini_set('memory_limit', -1);

class ApiResultadoBuscaController extends Controller
{
    
    public $enableCsrfValidation = false;


    public function actionResultadoBusca($busca_id=21){

        $sql = "SELECT
                c.id as id,
                m.id as matricula_id ,
                c.cpf as cliente_cpf,
                c.nome as cliente_nome,
                m.ocupacao as ocupacao,
                rb.margem as margem,
                rb.margem_disponivel as margem_disponivel,
                rb.margem_reservada as margem_reservada,
                rb.margem_cartao as margem_cartao,
                rb.margem_cartao_reservada as margem_cartao_reservada,
                rb.margem_cartao_disponivel as margem_cartao_disponivel,
                rb.margem_beneficio as margem_beneficio,
                rb.margem_beneficio_reservada as margem_beneficio_reservada,
                rb.margem_beneficio_disponivel as margem_beneficio_disponivel,
                rb.id as resultado_busca_id
            FROM 
                cliente c
            JOIN 
                matricula m
            ON
                c.id = m.cliente_id
            JOIN
                resultado_busca rb
            ON
                rb.matricula_id = m.id
            WHERE
                rb.busca_id = ".$busca_id
        ;

        $arrCliente = \Yii::$app->db->createCommand($sql)->queryAll();
        
        $arrClienteFinal =  [];
        
        $arrClienteFinal[] = [
            "CPF",
            "NOME",
            "OCUPACAO",
            "MARGEM",
            "MARGEM_RESERVADA",
            "MARGEM_DISPONIVEL",
            "MARGEM_CARTAO",
            "MARGEM_CARTAO_RESERVADO",
            "MARGEM_CARTAO_DISPONIVEL",
            "MARGEM_BENEFICIO",
            "MARGEM_BENEFICIO_RESERVADA",
            "MARGEM_BENEFICIO_DISPONIVEL"
        ];

        foreach($arrCliente as $cliente){
            $sqlTelefone = "SELECT id,CONCAT(ddd,'',telefone) as telefone FROM telefone WHERE cliente_id = " . $cliente["id"];
            $dadosTelefone = \Yii::$app->db->createCommand($sqlTelefone)->queryAll();
            #$arrTelefone = ArrayHelper::map($dadosTelefone, 'id', 'telefone');

            $sqlContrato = "SELECT * FROM ajcred.contrato WHERE resultado_busca_id = ".$cliente["resultado_busca_id"];
            $arrDadosContrato = \Yii::$app->db->createCommand($sqlContrato)->queryAll();


            $newCliente = [
                "cpf" => $cliente['cliente_cpf'] ,
                "nome" => $cliente['cliente_nome'],
                "ocupacao" => $cliente['ocupacao'],
                "margem" => str_replace(".",",",$cliente['margem']),
                "margem_reservada" => str_replace(".",",",$cliente['margem_reservada']),
                "margem_disponivel" => str_replace(".",",",$cliente['margem_disponivel']),
                "margem_cartao" => str_replace(".",",",$cliente['margem_cartao']),
                "margem_cartao_reservada" => str_replace(".",",",$cliente['margem_cartao_reservada']),
                "margem_cartao_disponivel" => str_replace(".",",",$cliente['margem_cartao_disponivel']),
                "margem_beneficio" => str_replace(".",",",$cliente['margem_beneficio']),
                "margem_beneficio_reservada" => str_replace(".",",",$cliente['margem_beneficio_reservada']),
                "margem_beneficio_disponivel" => str_replace(".",",",$cliente['margem_beneficio_disponivel']),
            ];


            $qtdCelulaTelefone = 8;


            for($i=0;$i<$qtdCelulaTelefone;$i++){
                $chave = "telefone_".$i;

                if(isset($dadosTelefone[$i])){
                    $newCliente[$chave] = $dadosTelefone[$i]['telefone'];
                }else{
                    $newCliente[$chave] = '';
                }
            }

            

            if(!empty($arrDadosContrato)){
                foreach($arrDadosContrato as $dadosContrato){
                    $newCliente['descricao_contrato'] = $dadosContrato['descricao'];
                    $newCliente['valor_credito_contrato'] = str_replace(".",",",$dadosContrato['valor_credito']);
                    $newCliente['valor_parcela_contrato'] = str_replace(".",",",$dadosContrato['valor_parcela']);
                    $newCliente['qtd_parcela_contrato'] = $dadosContrato['qtd_parcela'];
                    $newCliente['valor_pagar_contrato'] = str_replace(".",",",$dadosContrato['valor_pagar']);
                    $newCliente['nome_verba_contrato'] = $dadosContrato['nome_verba'];
                    $newCliente['parcela_pagar_contrato'] = $dadosContrato['parcela_pagar'];
                    $newCliente['parcela_paga_contrato'] = $dadosContrato['parcela_paga'];
                    $arrClienteFinal[] = $newCliente;
                }

            }else{
                $arrClienteFinal[] = $newCliente;
            }

        }

        $output = fopen( 'php://output', 'w' );

        ob_end_clean();


        /*
        header('Content-Type: text/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=csv_export.json');

        echo json_encode($arrClienteFinal);
        exit;
        */

        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=csv_export.csv');

        
        foreach($arrClienteFinal AS $data_item){
            fputcsv($output, $data_item);
        }
        exit();
        
    }


    public function actionGetDiferencaBusca($convenio_id=1){

        $convenio = Convenio::findOne(['id' => $convenio_id]);

        
        $arrBusca = Busca::find()->where(["convenio_id" => $convenio_id])->orderBy('id DESC')->limit(2)->all();
        
        $arrBusca = ArrayHelper::getColumn($arrBusca,'id','id');




        $nomeBuscaCache = 'diferenca_'.$convenio_id.'_'.$arrBusca[0].'_'.$arrBusca[1];
        $qtdCache = (int) Cache::find()->where(['nome' => $nomeBuscaCache])->count();


        if($qtdCache == 0){

            $sql = "SELECT
                    c.id as id,
                    m.id as matricula_id ,
                    c.cpf as cliente_cpf,
                    c.nome as cliente_nome,
                    m.ocupacao as ocupacao
                FROM 
                    cliente c
                JOIN 
                    matricula m
                ON
                    c.id = m.cliente_id
                WHERE
                    m.convenio_id = $convenio_id
                AND
                    c.id 
                NOT IN
                    (
                        SELECT 
                            crd.cliente_id
                        FROM
                            cliente_relatorio_diferenca crd
                        WHERE
                            YEAR(crd.data_inclusao) = ".date('Y')." 
                        AND 
                            MONTH(crd.data_inclusao) = ".date('m')."
                    )";
                    
            $arrCliente = \Yii::$app->db->createCommand($sql)->queryAll();

            $arrDiferenca = [];
            $arrClienteRelatorioDiferenca = [];

            

            foreach($arrCliente as $cliente){
                $matriculaId = (int)$cliente['matricula_id'];
                
                $dadosCliente = \Yii::$app->db->createCommand("SELECT margem,margem_cartao FROM resultado_busca WHERE busca_id = ".$arrBusca[0] . " AND matricula_id = ".$matriculaId)->queryOne();
                $dadosCliente2 = \Yii::$app->db->createCommand("SELECT margem,margem_cartao FROM resultado_busca WHERE busca_id = ".$arrBusca[1] . " AND matricula_id = ".$matriculaId)->queryOne();
                
                if(empty($dadosCliente) || empty($dadosCliente2))continue;

                if(($dadosCliente['margem'] != $dadosCliente2['margem']) 
                    || ($dadosCliente['margem_cartao'] != $dadosCliente2['margem_cartao'])){
                        

                        $margem_disponivel_anterior = str_replace(",",".",$dadosCliente2['margem']);
                        $margem_reservada_anterior = str_replace(",",".",$dadosCliente2['margem']);

                        $margem_anterior = str_replace(",",".",$dadosCliente2['margem']);
                        $margem_cartao_anterior = str_replace(",",".",$dadosCliente2['margem_cartao']);


                        $margem_atual = str_replace(",",".",$dadosCliente['margem']);
                        $margem_cartao_atual = str_replace(",",".",$dadosCliente['margem_cartao']);


                        $margem_anterior = floatval($margem_anterior);
                        $margem_atual = floatval($margem_atual);
                        
                        $margem_cartao_anterior = floatval($margem_cartao_anterior);
                        $margem_cartao_atual = floatval($margem_cartao_atual);


                        //Verifico se existe a diferença em alguma das margens para +40.00 positivo
                        $resultadoMargem = $margem_atual - $margem_anterior;
                        $resultadoMargemCartao = $margem_cartao_atual - $margem_cartao_anterior;
                        

                        $infoValida = false;
                        $valorMargemValida = 40;

                        if($margem_atual != $margem_anterior){
                            //Se a margem anterior for menor de que 0, considero a margem atual
                            if($margem_anterior < 0){
                                if($margem_atual >= $valorMargemValida){
                                    $infoValida = true;
                                }
                            }else{ // Margem Positiva
                                $resultadoMargem = $margem_atual - $margem_anterior;
                                if($resultadoMargem > $valorMargemValida){
                                    $infoValida = true;
                                }
                            }
                        }

                        
                        //|| $resultadoMargemCartao > 40 sómente margem
                        if($infoValida){

                            $arrClienteRelatorioDiferenca[] = [
                                $cliente['id'],
                                $convenio_id
                            ];

                            $cliente['margem_atual'] = $margem_atual;
                            $cliente['margem_anterior'] = $margem_anterior;
                            

                            $cliente['margem_cartao_atual'] = $margem_cartao_atual;
                            $cliente['margem_cartao_anterior'] = $margem_cartao_anterior;
                            
                            $arrDiferenca[] = $cliente;
                        }
                }
            }
            


            if(count($arrDiferenca)){
                foreach($arrDiferenca as $key=> $cliente){
                    
                    $sqlTelefone = "SELECT id,CONCAT(ddd,'',telefone) as telefone FROM telefone WHERE cliente_id = " . $cliente["id"];

                    $dadosTelefone = \Yii::$app->db->createCommand($sqlTelefone)->queryAll();

                    $arrValues = ArrayHelper::map($dadosTelefone, 'id', 'telefone');

                    $arrDiferenca[$key]['telefones'] = implode(",",$arrValues);
                }
            }

            

            if(count($arrClienteRelatorioDiferenca)){
                //Após gerar o relatório salvo os ids dos clientes com diferença no mês.
                \Yii::$app->db->createCommand()->batchInsert('cliente_relatorio_diferenca', ['cliente_id', 'convenio_id'], $arrClienteRelatorioDiferenca)->execute();
            }

            if(count($arrDiferenca)){
                /*
                Salvo a diferença dos dados
                */ 
                $objCache = new Cache();
                $objCache->nome = $nomeBuscaCache;
                $objCache->conteudo = json_encode($arrDiferenca);
                $objCache->save();
            }
            

            
            
            
        }else{
            $objCache = Cache::findOne(['nome' => $nomeBuscaCache]);
            $arrDiferenca = json_decode($objCache->conteudo,true);
        }
        

        if($arrDiferenca){
            //Verifico se a pasta existe se não existir eu crio
            if(!is_dir(\Yii::$app->params['dataFolder'])){
                mkdir(\Yii::$app->params['dataFolder']);
            }

            //Crio o arquivo CSV e salvo na pasta data agora é enviar ele por email
            $fileTempName = md5(microtime(true)).".csv";
            $fullPath = \Yii::$app->params['dataFolder'].'/'.$fileTempName;

            foreach($arrDiferenca AS $data_item){
                file_put_contents($fullPath,implode(",",$data_item).PHP_EOL,FILE_APPEND);
            }


            \Yii::$app->mailer->compose()
                ->setFrom('informativo@ajcred.com')
                ->setTo('junior@ajcred.com')
                //->setTo('ultraboxsilva38@gmail.com')
                ->setSubject('Relatório Diferença '.$convenio->nome)
                ->setTextBody('Olá, o relatório solicitado está em anexo. =D')
                ->attach($fullPath)
                ->send();

            
            
            if(is_file($fullPath)){
                unlink($fullPath);
            }
            
            
            \Yii::$app->getSession ()->setFlash ( 'success', \Yii::t ( 'app', 'Relatório solicitado enviado por email.' ) );
            return $this->redirect('index');
            
        }else{
            //echo "NENHUMA DIFERENÇA FOI ENCONTRADA";
            \Yii::$app->getSession ()->setFlash ( 'warning', \Yii::t ( 'app', 'Não foi encontrado nenhuma diferença nos relatórios' ) );
            return $this->redirect('index');
        }

        /*
        $output = fopen( 'php://output', 'w' );

        ob_end_clean();

        #$header_args = array( "ID", "Name", "Email" );

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=csv_export.csv');

        
        foreach($arrDiferenca AS $data_item){
            fputcsv($output, $data_item);
        }

        exit();
        */
    }


    /**
     * Relatório quantitativo de clientes e contratos exportado
     * via CSV
     * Por padrão coloquei o convenio do tipo 7 que seria o RIO CONSIG
     */
    public function actionGetRelatorioQuantitativoClienteContrato($convenio_id=7){
    #public function actionGetRelatorioQuantitativo($convenio_id=7){
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        /**
         *  Pego os clientes e as matriculas desse convenio
         */

        $query = ModelsCliente::find()
                    ->select(['cliente.id as cliente_id','cliente.nome cliente_nome','cliente.cpf cliente_cpf','matricula.id matricula_id', 'matricula.matricula matricula'])
                    ->join('INNER JOIN', 'matricula', 'cliente.id = matricula.cliente_id')
                    ->andWhere(['matricula.convenio_id' => $convenio_id])
                    ->andWhere(['matricula.is_ativo' => 1]);

        #$sql = $query->createCommand()->getRawSql();


        $arrClienteFinal = [];

        $colunasRelatorio = [
            'Cpf',
            'Nome',
            'Matricula',
            'Margem Empréstimo',
            'Margem Empréstimo Reservada',
            'Margem Empréstimo Disponível',
            'Margem Cartão',
            'Margem Cartão Reservada',
            'Margem Cartão Disponível',
            'Margem Benefício',
            'Margem Benefício Reservada',
            'Margem Benefício Disponível',
            'Quantidade de Contratos'
        ];

        $arrClienteFinal[] = $colunasRelatorio;

        $arrClienteMatricula = $query
                        ->asArray()
                        ->all();
         

        foreach($arrClienteMatricula as $clienteMatricula){
            
            #PEGO O RESULTADO DA ULTIMA BUSCA
            $queryResultadoBusca = ResultadoBusca::find()
                            ->select(['id','margem','margem_disponivel','margem_reservada','margem_cartao','margem_cartao_reservada','margem_cartao_disponivel','margem_beneficio','margem_beneficio_reservada','margem_beneficio_disponivel'])
                            ->where(['matricula_id' => $clienteMatricula['matricula_id']])
                            ->orderBy(['id' => SORT_DESC]);

            $resultadoBusca = $queryResultadoBusca->asArray()->one();

            #SE ENCONTREI DADOS eu coloco no relatorio, pra ir gerando de acordo com que for sendo inserido na base em caso do banco ainda não estar alimentado.
            if($resultadoBusca){
                #Vejo agora um quantitativo de contratos
                $queryQuantitativoContrato = Contrato::find()
                                        ->select(['id'])
                                        ->where(['resultado_busca_id' => $resultadoBusca['id']]);
                $qtdContratos = $queryQuantitativoContrato->count();

                /**
                 *  'Cpf',
                    'Nome',
                    'Matricula',
                    'Margem Empréstimo',
                    'Margem Empréstimo Reservada',
                    'Margem Empréstimo Disponível',
                    'Margem Cartão',
                    'Margem Cartão Reservada',
                    'Margem Cartão Disponível',
                    'Margem Benefício',
                    'Margem Benefício Reservada',
                    'Margem Benefício Disponível',
                    'Quantidade de Contratos'
                 */
                $qtd = (int) $queryQuantitativoContrato->count();

                $arrToInsert = [
                    $clienteMatricula['cliente_cpf'],
                    $clienteMatricula['cliente_nome'],
                    $clienteMatricula['matricula'],
                    $resultadoBusca['margem'],
                    $resultadoBusca['margem_reservada'],
                    $resultadoBusca['margem_disponivel'],
                    $resultadoBusca['margem_cartao'],
                    $resultadoBusca['margem_cartao_reservada'],
                    $resultadoBusca['margem_cartao_disponivel'],
                    $resultadoBusca['margem_beneficio'],
                    $resultadoBusca['margem_beneficio_reservada'],
                    $resultadoBusca['margem_beneficio_disponivel'],
                    $qtd,
                ];
                $arrClienteFinal[] = $arrToInsert;
            }
        }


        $output = fopen( 'php://output', 'w' );
        ob_end_clean();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=relatorio_quantitativo.csv');

        
        foreach($arrClienteFinal AS $data_item){
            fputcsv($output, $data_item);
        }
        exit();
    }

    
    public function actionSaveMultiMargem($busca_id){

        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        if(\Yii::$app->request->isPost){
            $resultadoBusca = new ResultadoBusca();
            $resultadoBusca->matricula_id = $_POST['matricula_id'];
            $resultadoBusca->busca_id = $busca_id;
            
            $resultadoBusca->margem = $_POST['margem'];
            $resultadoBusca->margem_disponivel = $_POST['margem_disponivel'];
            $resultadoBusca->margem_reservada = $_POST['margem_reservada'];
            
            $resultadoBusca->margem_cartao = $_POST['margem_cartao'];
            $resultadoBusca->margem_cartao_reservada = $_POST['margem_cartao_reservada'];
            $resultadoBusca->margem_cartao_disponivel = $_POST['margem_cartao_disponivel'];
            
            $resultadoBusca->margem_beneficio = $_POST['margem_beneficio'];
            $resultadoBusca->margem_beneficio_reservada = $_POST['margem_beneficio_reservada'];
            $resultadoBusca->margem_beneficio_disponivel = $_POST['margem_beneficio_disponivel'];
            $resultadoBusca->save();

            return ["msg" => "SALVO COM SUCESSO", "id" => $resultadoBusca->id];
        }
        
        return ["msg" => "Requisição precisa ser um POST"];
    }



    public function actionSaveContrato($resultado_busca_id){
        $this->layout = '';

        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;


        $arrContrato = $_POST['contrato'];
        $arrContrato = trim($arrContrato);

        if(!empty($arrContrato)){
            $arrContrato = json_decode($arrContrato,true);
            foreach($arrContrato as $contrato){

                
                if(!isset($contrato['qtdAberta'])){
                    $qtdParcelaPagar = $contrato['vlrAPagar'] / $contrato['vlrParcela'];
                }else{
                    $qtdParcelaPagar = $contrato['qtdAberta'];
                }
                
                
                if(!isset($contrato['qtdPaga'])){
                    $qtdPaga = $contrato['qtdParcela'] - $qtdParcelaPagar;
                }else{
                    $qtdPaga = $contrato['qtdPaga'];
                }
                
                $objContrato = new Contrato();
                $objContrato->descricao = $contrato['desContrato'];
                $objContrato->valor_credito = $contrato['vlrCredito'];
                $objContrato->valor_parcela = $contrato['vlrParcela'];
                $objContrato->qtd_parcela = $contrato['qtdParcela'];
                $objContrato->valor_pagar = $contrato['vlrAPagar'];
                $objContrato->nome_verba = $contrato['nomeVerba'];
                $objContrato->parcela_paga = $qtdPaga;
                $objContrato->parcela_pagar = $qtdParcelaPagar;
                $objContrato->status_contrato = $contrato['statusContrato'];
                $objContrato->juros_anual = $contrato['jurosAnual'];
                $objContrato->juros_mensal = $contrato['jurosMensal'];

                $objContrato->resultado_busca_id = $resultado_busca_id;
                $objContrato->save();

                var_dump($objContrato->getErrors());
            }
        }


    }


    /**
     * RETORNA TODAS AS BUSCAS DISPONIVEIS
     */
    public function actionSave($busca_id){

        $this->layout = '';

        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $resultadoBusca = new ResultadoBusca();
        $resultadoBusca->margem = $_POST['margemDisponivel'];
        $resultadoBusca->margem_cartao = $_POST['margemDisponivelCartao'];
        $resultadoBusca->busca_id = $busca_id;
        $resultadoBusca->matricula_id = $_POST['matricula_id'];

        $res = $resultadoBusca->save();

        $arrContrato = $_POST['contrato'];
        $arrContrato = trim($arrContrato);

        if(!empty($arrContrato)){
            $arrContrato = json_decode($arrContrato,true);
            foreach($arrContrato as $contrato){

                /**
                 * @todo Fazer o calculo do que falta pagar, 
                 */
                
                if(!isset($contrato['qtdAberta'])){
                    $qtdParcelaPagar = $contrato['vlrAPagar'] / $contrato['vlrParcela'];
                }else{
                    $qtdParcelaPagar = $contrato['qtdAberta'];
                }
                
                
                if(!isset($contrato['qtdPaga'])){
                    $qtdPaga = $contrato['qtdParcela'] - $qtdParcelaPagar;
                }else{
                    $qtdPaga = $contrato['qtdPaga'];
                }
                

            

                $objContrato = new Contrato();
                $objContrato->descricao = $contrato['desContrato'];
                $objContrato->valor_credito = $contrato['vlrCredito'];
                $objContrato->valor_parcela = $contrato['vlrParcela'];
                $objContrato->qtd_parcela = $contrato['qtdParcela'];
                $objContrato->valor_pagar = $contrato['vlrAPagar'];
                $objContrato->nome_verba = $contrato['nomeVerba'];
                $objContrato->parcela_paga = $qtdPaga;
                $objContrato->parcela_pagar = $qtdParcelaPagar;

                $objContrato->resultado_busca_id = $resultadoBusca->id;
                $objContrato->save();

                var_dump($objContrato->getErrors());
            }
        }

        
        #var_dump($res,$resultadoBusca->);
        exit;

    }

    
}

