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
use app\models\Convenio;
use app\models\Email;
use app\models\Endereco;
use app\models\Matricula;
use app\models\ResultadoBusca;
use app\models\Telefone;
use yii\helpers\ArrayHelper;
use yiibr\brvalidator\CpfValidator;

ini_set('memory_limit', -1);


DEFINE('CECON_ID' , 1);
DEFINE('PREFEITURA_CUIABA_ID',2);


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{   

    public function actionRotina(){

        $arrName = [
            "Empréstimo",
            "Refinanciamento",
            "Portabilidade",
            "Cartão",
            "Cartão Benefício",
            "Saque Complementar",
            "Saque FGTS",
            "INSS",
            "SIAPE",
            "PREF CUIABÁ",
            "GOV MS",
            "GOV AM",
            "GOV RO"
        ];

        $handle = fopen("produtos.csv", "r");
        if ($handle) {
            $contador = 0;
            while (($line = fgets($handle)) !== false) {
                
                $contador++;

                $explodeLine = explode(";",$line);
                
                if($contador == 1)continue;
                
                #if($contador != 2 || $contador != 3)continue;
                #if($contador == 3)continue;

                $line = trim($line);
                
                foreach($arrName as $key => $name){
                    var_dump($name);
                    $data = $explodeLine[$key+1];
                    $data = trim($data);
                    
                    if(!empty($data)){
                        $explodeData = explode(",",$data);
                        

                        $jsonPostData = [
                            "name" => $name,
                            "installments" => $explodeData,
                            "fkBankId" =>   $contador - 1                        
                        ];

                        
                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, 'http://srv.ajcred.local:3000/api/v1/products');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsonPostData));

                        $headers = array();
                        $headers[] = 'Accept: /';
                        $headers[] = 'Content-Type: application/json';
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close($ch);

                        var_dump($result);
                    }
                }
            }

            fclose($handle);
        }

    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }


    public function actionComparaDados(){

        $arrBusca = [2,21];

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
                    m.convenio_id = 1;";
                    
        $arrCliente = \Yii::$app->db->createCommand($sql)->queryAll();

        $arrDiferenca = [];

        foreach($arrCliente as $cliente){
            $matriculaId = (int)$cliente['matricula_id'];
            
            $dadosCliente = \Yii::$app->db->createCommand("SELECT margem,margem_cartao FROM resultado_busca WHERE busca_id = ".$arrBusca[0] . " AND matricula_id = ".$matriculaId)->queryOne();
            $dadosCliente2 = \Yii::$app->db->createCommand("SELECT margem,margem_cartao FROM resultado_busca WHERE busca_id = ".$arrBusca[1] . " AND matricula_id = ".$matriculaId)->queryOne();
            

            if(($dadosCliente['margem'] != $dadosCliente2['margem']) 
                || ($dadosCliente['margem_cartao'] != $dadosCliente2['margem_cartao'])){

                    $cliente["margem_anterior"] = $dadosCliente['margem'];
                    $cliente["margem_atual"] = $dadosCliente2['margem'];

                    $cliente["margem_cartao_anterior"] = $dadosCliente['margem_cartao'];
                    $cliente["margem_cartao_atual"] = $dadosCliente2['margem_cartao'];

                    $arrDiferenca[] = $cliente;
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

        return $arrDiferenca;
        
    }


    public function actionImportaTelefone(){
        $handle = fopen('gov_ro.csv', "r"); 
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $explodeLine = explode(";",$line);
                
                if($explodeLine[0] == 'NOME' || $explodeLine[0] == '')continue;

                $cpf = $explodeLine[1];
                $cpf = str_replace('.','',$cpf);
                $cpf = str_replace('-','',$cpf);


                /*
                NOME; - 0
                CPF; - 1
                MR EMPR; - 2
                CREDITO APROX; - 3
                FONE; - 4
                FONE2; - 5
                FONE3; - 6
                FONE4 - 7
                */


                $cliente = Cliente::findOne(['cpf' => $cpf]);

                if($cliente){
                    
                    $arrPos =  [
                        4,
                        5,
                        6,
                        7
                    ];

                    foreach($arrPos as $pos){

                        if(isset($explodeLine[$pos]) && !empty($explodeLine[$pos])){
                            $numero = $explodeLine[$pos];
                            
                            $ddd = substr($numero,0,2);
                            $telefone = substr($numero,2);

                            $objTelefone = new Telefone();
                            $objTelefone->telefone = $telefone;
                            $objTelefone->ddd = $ddd;
                            $objTelefone->cliente_id = $cliente->id;
                            $objTelefone->save();

                            echo $ddd.PHP_EOL;
                            echo $telefone.PHP_EOL;
                        }
                    }
                }
            }

            fclose($handle);
        }

    }

    public function actionImportaGovernoMs(){
        $handle = fopen('csvs_importados/GOVMS.csv', "r");
        $contador = 0; 
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                
                $contador++;

                if($contador==1)continue;

                $line = trim($line);
                            
                $cpf = str_pad($line, 11, "0", STR_PAD_LEFT);
                $cpf = trim($cpf);

                //VERIFICO SE TEM O CPF NA BASE
                

                var_dump($cpf);

                exit();

                

            }
        }

    }


    public function actionImporta()
    {

        $content = file_get_contents('margem.json');

        $json_content = json_decode($content,true);
        
        
        
        foreach($json_content as $content){


            $model = new CLIENTE();
            $model->nome = $content['nome'];
            $model->cpf = $content['cpf'];
            
            $model->save();

            echo $model->id.PHP_EOL;

            $matricula = new Matricula();
            $matricula->matricula = $content['matricula'];
            $matricula->ocupacao = trim($content['nomeCargo']);
            $matricula->cliente_id = $model->id;
            $matricula->convenio_id = 1;

            $matricula->save();
        }
    }


    public function actionImportaCuiabaMaster(){
        $handle = fopen("csvs_importados/CUIABA_BANCO_MASTER_RESULTADO.csv", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $explodeLine = explode(";",$line);

                $nome = $explodeLine[0];
                $nome = trim($nome);

                $cpf = str_pad($explodeLine[1], 11, "0", STR_PAD_LEFT);
                $cpf = trim($cpf);


                $dataNascimento = $explodeLine[3];
                $dataNascimento= trim($dataNascimento);
                

                $matricula = $explodeLine[2];
                $matricula = trim($matricula);

                $ocupacao = $explodeLine[7];
                $ocupacao = trim($ocupacao);


                //Procuro pelo CPF no banco de dados
                $qtdCliente  = \app\models\Cliente::find()->where(['cpf' => $cpf])->count();
                $qtdCliente = (int)$qtdCliente;


                if($qtdCliente == 0 ){
                    $objCliente = new CLIENTE();
                    $objCliente->cpf = $cpf;
                    $objCliente->nome = $nome;
                    $objCliente->data_nascimento = $dataNascimento;
                    $savedCliente = $objCliente->save();
                }

                //PROCURO PELA MATRICULA
                $objMatricula = Matricula::findOne(['matricula' => $matricula]);
                
                
                if(!$objMatricula){
                    $objMatricula = new Matricula();
                    $objMatricula->convenio_id = PREFEITURA_CUIABA_ID;
                    $objMatricula->cliente_id = $objCliente->id;
                    $objMatricula->matricula = $matricula;
                    $objMatricula->ocupacao = $ocupacao;
                }else{
                    $objMatricula->cliente_id = $objCliente->id;
                }

                $objMatricula->save();

            }

            fclose($handle);
        }

    }

    public function actionImportaCpfMasterCsv2(){
        $handle = fopen("csvs_importados/CUIABA_BANCO_MASTER_RESULTADO.csv", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $explodeLine = explode(";",$line);
                $cpf = $explodeLine[1];
                $cpf = str_pad($cpf , 11, "0", STR_PAD_LEFT);

                $count = Cliente::find()->where(['cpf' => $cpf ])->count();
                $count = (int)$count;
                

                if($count>0)continue;

                var_dump($cpf);exit;

                var_dump($cpf);
            }
        }
    }

    public function actionImportaCpfMaster2(){
        $handle = fopen("csvs_importados/validos_banco_master_1.txt", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $explodeLine = explode("|",$line);
                $cpf = $explodeLine[0];
                $cpf = str_pad($cpf , 11, "0", STR_PAD_LEFT);
                
                $count = Cliente::find()->where(['cpf' => $cpf ])->count();
                $count = (int) $count;
                
                if($count > 0)continue;

                $objCliente = new Cliente();
                $objCliente->cpf = $cpf;
                $res = $objCliente->save();

                var_dump($res,$cpf);
            }
        }
    }


    public function actionRemoveDuplicidade(){
        $sql = "select 
                    cpf
                from 
                    (select 
                        count(id) as contador,
                        cpf,
                        nome
                    from 
                        ajcred.cliente
                    group by
                        cpf,nome
                    having 
                        contador > 1
                    order by
                        contador DESC) as tb";

        $result = \Yii::$app->db->createCommand($sql)->queryAll();

        foreach($result as $r){
            $cpf = trim($r['cpf']);

            var_dump($cpf);
            
            exit;
        }

        exit;
    }

    public function formatarData($data) {
        // Converte a string de data para um timestamp
        $timestamp = strtotime($data);
    
        // Formata o timestamp no formato desejado
        $dataFormatada = date('d/m/Y', $timestamp);
    
        return $dataFormatada;
    }


    public function actionDesativaMatriculaRioConsig(){
        $nomeArquivo = 'csvs_importados/cpf-matricula-invalido-rioconsig.txt';

        try {
            $arquivo = fopen($nomeArquivo, 'r');
            
            if ($arquivo) {
                while (($linha = fgets($arquivo)) !== false) {
                    // Processa cada linha conforme necessário
                    $linha = trim($linha);
                    $jsonLinha = json_decode($linha,true);  

                    $cpf = $jsonLinha['cpf'];
                    $matricula= $jsonLinha['matricula'];

                    $objMatricula = Matricula::find()
                            ->joinWith(['cliente'])
                            ->andWhere(['cliente.cpf' => $cpf])
                            ->andWhere(['matricula.matricula' => $matricula])
                            ->one();

                    if($objMatricula){
                        $objMatricula->is_ativo = 0;
                        $objMatricula->save();
                        var_dump($objMatricula->getErrors());
                    }
                }
                
                fclose($arquivo);
            } else {
                echo "Não foi possível abrir o arquivo.";
            }
        } catch (\Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage();
        }
    }

    public function actionImportaRioConsig(){

        $convenioId = false;

        $convenio = Convenio::find()->where(['nome' => 'Rioconsig'])->one();

        if(!$convenio){
            $objConvenio = new Convenio();
            $objConvenio->nome = 'RioConsig';
            $objConvenio->sigla = 'RIOCONSIG';
            $objConvenio->save();
            echo 'CONVÊNIO RIOCONSIG ADICIONADO';

            $convenioId = $objConvenio->id;
        }else{
            $convenioId = $convenio->id;
        }

        $handle = fopen("csvs_importados/dados-basicos.txt", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $jsonRes = json_decode($line,true);
                
                if($jsonRes){

                    $clienteId = false;


                    $pessoa  = $jsonRes['data']['pessoa'];
                    $cpf = trim($pessoa['pes_cpf']);
                    $matricula = trim($jsonRes['data']['matricula']);

                    //Primeiro Verifico se tem alguma pessoa com o cpf já existente na base
                    $cliente = Cliente::find()
                                ->select(['id'])
                                ->where(['cpf' => $cpf ])
                                ->one();
                
                    if($cliente){
                        echo 'CPF '.$cpf.' JÁ CADASTRADO NA BASE';
                        echo 'MATRICULA '.$matricula.PHP_EOL;
                        $clienteId = $cliente->id;
                    }else{
                        $pessoa  = $jsonRes['data']['pessoa'];
                        $objCliente = new CLIENTE();
                        $objCliente->nome = trim($pessoa['pes_nome']);
                        $objCliente->cpf = $cpf;
                        $objCliente->data_nascimento = trim($this->formatarData($pessoa['pes_dt_nascimento']));
                        $objCliente->save();
                        $clienteId = $objCliente->id;

                        echo 'CPF '.$cpf.'CADASTRADO COM SUCESSO'.PHP_EOL;
                    }
                    
                    //Verifico se tem email vinculado
                    $emailStr = trim($jsonRes['data']['email']);
                    if($emailStr != ''){
                        $countEmail = Email::find()->where(['cliente_id' => $clienteId, 'email' => $emailStr])->count();
                        $countEmail = (int) $countEmail;
                        
                        //Não tem email cadastrado na base
                        if($countEmail == 0){
                            $objEmail = new Email();
                            $objEmail->email = $emailStr;
                            $objEmail->cliente_id = $clienteId;
                            $objEmail->save();

                            echo $objEmail->id.'EMAIL DO CPF '.$cpf.'CADASTRADO COM SUCESSO'.PHP_EOL;
                        }

                    }
                    
                    //Agora vejo se a matricula já se encontra cadastrada na base de dados
                    $resMatricula = Matricula::find()->where(['convenio_id' => $convenioId, 'matricula' => $matricula, 'cliente_id' => $clienteId])->one();
                    
                    var_dump($matricula);

                    //Caso não tenha sido cadastrado eu faço o cadastro na base
                    if(!$resMatricula){
                        $objMatricula = new Matricula();
                        $objMatricula->convenio_id = $convenioId;
                        $objMatricula->cliente_id = $clienteId;
                        $objMatricula->matricula = $matricula;
                        $objMatricula->ocupacao = trim($jsonRes['data']['secretaria']).' '.trim($jsonRes['data']['lotacao']);
                        $objMatricula->is_ativo = (trim($jsonRes['data']['situacao']) == "ATIVO") ? 1 : 0;
                        $objMatricula->save();


                        echo $objMatricula->id.' Matricula cadastrada com sucesso'.PHP_EOL;
                    }


                    continue;

                    
                }
                //var_dump();
            }
        }
    }


    public function actionImportaCpfMaster(){
        $handle = fopen("csvs_importados/validos_odontoprev_1.txt", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $explodeLine = explode(",",$line);
                
                var_dump($line);

                if(count($explodeLine) < 2)continue;
                
                $cpf = $line[0];
                $cpf = trim($cpf);
                $cpf = str_pad($explodeLine[1], 11, "0", STR_PAD_LEFT);
                
                

                $nome = $line[1];
                $nome = trim($nome);

                $email = $line[2];
                $email = trim($email);

                $count = Cliente::find()->where(['cpf' => $cpf ])->count();
                $count = (int) $count;

                if($count > 0)continue;
                
                $objCliente = new Cliente();
                $objCliente->cpf = $cpf;
                $objCliente->nome = $nome;
                $res = $objCliente->save();

                if($res){
                    echo $cpf.PHP_EOL;
                }

                if(!empty($email)){
                    $objEmail = new Email();
                    $objEmail->email = $email;
                    $objEmail->cliente_id = $objCliente->id;
                    $objEmail->save();
                }
            }
        }
    }


    /**
     * DESATIVA TODAS MATRICULAS QUE ESTÃO 
     * INCLUSAS NO ARQUIVO LIMPAR_BANCO_AJCRED
     * 
     */
    public function actionLimpezaMatriculaByNomeOcupacao(){
        $content = file_get_contents('LIMPAR_BANCO_AJCRED.txt');
        $arrOcupacao = explode(PHP_EOL,$content);

        $arrOcupacaoFinal = [];
        foreach($arrOcupacao as $ocupacao){
            $ocupacao = trim($ocupacao);
            $arrMatricula = Matricula::findAll(['ocupacao' => $ocupacao]);

            foreach($arrMatricula as $matricula){
                $matricula->is_ativo = 0;
                $matricula->save();
            }
        }
    }


    public function actionImportaEconsig(){
        $content = file_get_contents('csvs_importados/econsig.csv');
        $lines = explode(PHP_EOL,$content);

        foreach($lines as $line){
            $line = trim($line);
            $explodeLine = explode(";",$line);
            $matriculaNome = trim($explodeLine[2]);
            $matriculaNomeExplode = explode(" - ",$matriculaNome);
            if(count($matriculaNomeExplode) < 2)continue;
            $strMatricula = trim($matriculaNomeExplode[0]);
            $nome = trim($matriculaNomeExplode[1]);
            $cpf = trim($explodeLine[3]);
            $ocupacao = trim($explodeLine[4]);

            echo $cpf.PHP_EOL;
            $cliente = Cliente::findOne(['cpf' => $cpf]);
            if(!$cliente){

                $cliente = new CLIENTE();
                $cliente->nome = $nome;
                $cliente->cpf = $cpf;
                $cliente->save();
            }

            $matricula = Matricula::findOne(["matricula" => $strMatricula]);
            if(!$matricula){
                $matricula = new Matricula();
                $matricula->matricula = $strMatricula;
                $matricula->ocupacao = $ocupacao;
                $matricula->cliente_id = $cliente->id;
                $matricula->convenio_id = 4;
                $matricula->save();

                var_dump($matricula->getErrors());
            }else{
                $matricula->cliente_id = $cliente->id;
                $matricula->save();
            }
            
        }
    }



    public static function validateCpf($value)
    {
        $valid = true;
        $cpf = preg_replace('/[^0-9]/', '', $value);

        for($x = 0; $x < 10; $x ++) {
            if ($cpf == str_repeat ( $x, 11 )) {
                $valid = false;
            }
        }
        if ($valid) {
            if (strlen ( $cpf ) != 11) {
                $valid = false;
            } else {
                for ($t = 9; $t < 11; $t ++) {
                    $d = 0;
                    for($c = 0; $c < $t; $c ++) {
                        $d += $cpf[$c] * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;
                    if ($cpf[$c] != $d) {
                        $valid = false;
                        break;
                    }
                }
            }
        }
        return $valid;
    }

    /**
     * Popula a base com os dados json do bnaco master 
     * importado
     * 
     */
    public function actionPopulaDadosJsonBancoMasterImportado(){

        $handle = fopen("csvs_importados/json_banco_master_consultado.csv", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $line = substr($line,1,strlen($line)-2);
                $jsonDecode = json_decode($line,true);
                if(!isset($jsonDecode['cpf']))continue;
                $cpf = $jsonDecode['cpf'];
                $cpf = str_replace(".","",$cpf);
                $cpf = str_replace("-","",$cpf);

            
                $cliente = CLIENTE::findOne(['cpf' => $cpf]);

                    
                if($cliente){

                    //VERIFICO SE O CLIENTE TEM A MATRICULA CADASTRADA
                    if(isset($jsonDecode['dadosCadastrais']['matricula'])){
                        $matricula = trim($jsonDecode['dadosCadastrais']['matricula']);
                        //Verifico se a matricula foi cadastrada
                        $existsMatricula = Matricula::find()->where(['matricula' => $matricula , "convenio_id" => 3, "cliente_id" => $cliente->id])->exists(); 
                        if(!$existsMatricula){
                            $objMatricula = new Matricula();
                            $objMatricula->convenio_id = 3;
                            $objMatricula->cliente_id = $cliente->id;
                            $objMatricula->matricula = $matricula;
                            $objMatricula->save();
                        }
                    }

                    if($cliente->importado_banco_master == 1)continue;


                    echo $cliente['cpf'].PHP_EOL;

                    //Salvo o nome 
                    $cliente->nome = $jsonDecode['nome'];


                    if(isset($jsonDecode['dadosCadastrais'])){
                            //dataNascimento
                        if(isset($jsonDecode['dadosCadastrais']['nascimento'])){
                            $dataNascimento = $jsonDecode['dadosCadastrais']['nascimento'];
                            $dataNascimento = substr($dataNascimento,0,10);
                            $dataNascimento = trim($dataNascimento);
                            $ano = substr($dataNascimento,0,4);
                            $mes = substr($dataNascimento,5,2);
                            $dia = substr($dataNascimento,8,2);
                            $dataNascimentoFormatada = $dia.'/'.$mes.'/'.$ano;
                            $cliente->data_nascimento = $dataNascimentoFormatada;
                        }

                        //SALVA OBJETO DO CLIENTE NO BANCO
                        $cliente->save(); 

                        //EMAIL
                        if(isset($jsonDecode['dadosCadastrais']['email'])){
                            $email = $jsonDecode['dadosCadastrais']['email'];
                            $email = trim($email);
                            $objEmail = new Email();
                            $objEmail->email = $email;
                            $objEmail->cliente_id = $cliente->id;
                            $objEmail->save();
                        }

                        //ENDEREÇO
                        $bairro = $jsonDecode['dadosCadastrais']['bairro'];
                        $logradouro = $jsonDecode['dadosCadastrais']['logradouro'];
                        $numero  = $jsonDecode['dadosCadastrais']['numero'];
                        $cidade = $jsonDecode['dadosCadastrais']['cidade'];
                        $uf = $jsonDecode['dadosCadastrais']['uf'];
                        $cep  =$jsonDecode['dadosCadastrais']['cep'];

                        
                        //Monta o objeto endereço
                        $objEndereco = new Endereco();
                        $objEndereco->bairro = $bairro;
                        $objEndereco->numero = $numero;
                        $objEndereco->endereco = $logradouro;
                        $objEndereco->cidade = $cidade;
                        $objEndereco->uf = $uf;
                        $objEndereco->cep =  $cep;
                        $objEndereco->cliente_id = $cliente->id;
                        $objEndereco->save();
                        

                        if(isset($jsonDecode['dadosCadastrais']['celular'])){
                            $celular = $jsonDecode['dadosCadastrais']['celular'];
                            $celular = trim($celular);
                            if(!empty($celular)){

                                $dddCelular = substr($celular,0,2);
                                $numeroCelular = substr($celular,2);

                                $objTelefone = new Telefone();
                                $objTelefone->ddd = $dddCelular;
                                $objTelefone->cliente_id = $cliente->id;
                                $objTelefone->telefone = $numeroCelular;
                                $objTelefone->save();
                            }
                        }
                        
                        
                        $cliente->importado_banco_master = 1;

                        $cliente->flg_consulta_master = 1;

                        $cliente->json_banco_master = json_encode($jsonDecode);

                        $cliente->save();

                    }
                }
            }
        }
    }

    public function actionRemovePontoTracoBase(){
        $arrCliente = Cliente::find()->select(['id','cpf'])->all();

        $contadorCpfInvalido = 0;
        foreach($arrCliente as $cliente){
            $cpf = $cliente->cpf;

            if(strstr($cpf,".")){

                $novoCpf = str_replace(".","",$cpf);
                $novoCpf = str_replace("-","",$novoCpf);


                $cliente->cpf = $novoCpf;
                $cliente->save();


                var_dump($cliente->id);

                
            }

            continue;

            
            
            $cliente->cpf = $novoCpf;
            $cliente->save(false);


            var_dump($cliente->getErrors());

            

            
            $isValid = self::validateCpf($novoCpf);
            if(!$isValid){
                $cliente->delete();
                $contadorCpfInvalido++;
            }
            

            var_dump($contadorCpfInvalido);

            #var_dump($novoCpf);
        }


    }

    /**
     * Rotina de importação de clientes do banco master
     * @trocadebase
     */
    public function actionImportaMysqlBancoMaster(){
        $handle = fopen("csvs_importados/todos_cpfs.txt", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $valid = self::validateCpf($line);
                $cpf = $line;

                //Verifico se é um cpf válido
                if($valid){


                    //Preciso verificar se o json do banco master foi baixado
                    $cliente = CLIENTE::find()->select(['id','cpf','json_banco_master','flg_consulta_master'])->where(["cpf" => $cpf])->one();

                    if($cliente){

                        echo $cliente->cpf.PHP_EOL;

                        //echo $cliente->flg_consulta_master.PHP_EOL;

                        //Se for vazio eu printo na tela
                        if(empty($cliente->json_banco_master)){
                            $cliente->flg_consulta_master = 3;
                            $cliente->save(false);

                            var_dump($cliente->id);

                        }
                        
                    }
                    
                    continue;


                    $exists = CLIENTE::find()->where(["cpf" => $cpf])->exists();
                    if(!$exists){
                        
                        $cliente = new CLIENTE();
                        $cliente->cpf = $cpf;
                        $saved = $cliente->save();

                        var_dump($saved);
                        

                    }else{
                        echo "CPF JÁ CADASTRADO".PHP_EOL;
                        
                    }
                }

                //var_dump($line,$valid);
            }
        }
        
    }


    public function actionImportaEmail(){
        $handle = fopen("TESTE_CECON.csv", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $explode = explode(";",$line);

                if($explode[0] == 'NOME/RAZAOSOCIAL')continue;

                $cpf = str_pad($explode[1], 11, "0", STR_PAD_LEFT);

                $cliente = Cliente::findOne(['cpf' => $cpf]);

                if(empty($cliente))continue;
                
                $arrPos = [
                    56,
                    57,
                    58
                ];

                foreach($arrPos as $pos){
                    if(isset($explode[$pos]) && !empty($explode[$pos])){
                        $email = $explode[$pos];
                        $email = trim($email);
                        
                        $objEmail = new Email();
                        $objEmail->email = $email;
                        $objEmail->cliente_id = $cliente->id;
                        $objEmail->save();
                    }
                }
            
            }

            fclose($handle);
        }
    }

}
