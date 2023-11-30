<?php

namespace app\controllers;

use app\models\ImportacaoLote;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\Cliente;


class ImportacaoController extends Controller
{
    

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

    public function actionCliente()
    {
        $model = new ImportacaoLote();

        $contadorCpfNovo = 0;
        $contadorCpfExiste = 0;

        if (\Yii::$app->request->isPost) {
            $model->arquivo = UploadedFile::getInstance($model, 'arquivo');
            if ($filePath = $model->upload()) {
                
                $handle = fopen($filePath, "r");
                if ($handle) {
                    while (($line = fgets($handle)) !== false) {
                        $cpf = trim($line);
                        $isValida = self::validateCpf($line);

                        if($isValida){
                            $exists = Cliente::find()->where(['cpf' => $cpf])->exists();
                            if($exists){
                                $contadorCpfExiste++;
                            }else{
                                $contadorCpfNovo++;
                                $cliente = new Cliente();
                                $cliente->cpf = $cpf;
                                $cliente->save();
                            }
                        }
                    }
                    fclose($handle);
                }

                //Faço a exclusão do arquivo já importado
                unlink($filePath);

                \Yii::$app->getSession ()->setFlash ( 'success', \Yii::t ( 'app', 'Foram inseridos '.$contadorCpfNovo. ' novos cpfs' ) );
                \Yii::$app->getSession ()->setFlash ( 'warning', \Yii::t ( 'app', 'Foram encontrados '.$contadorCpfExiste . ' cpfs já cadastrados' ) );
                // file is uploaded successfully
                return $this->redirect(['cliente']);
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    
}
