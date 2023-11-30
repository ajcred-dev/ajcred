<?php

use app\models\Contrato;
use app\models\ResultadoBusca;
use kartik\helpers\Html;
use kartik\icons\Icon;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Cliente', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cliente-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
    </div>


    <br>


    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5>Dados Básicos</h5>
                </div>
                <div class="card-body">

                    <div class="widget-view-sub"><i class="icon-file"></i> <b>Documentos</b><br>
                        <div class="profile-info">
                            <ul class="unstyled">
                                <li>CPF: <?php echo $model->cpf ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="widget-view-sub"><i class="icon-file"></i> <b>Data de Nascimento</b><br>
                        <div class="profile-info">
                            <ul class="unstyled">
                                <li><?php echo $model->data_nascimento ?></li>
                            </ul>
                        </div>
                    </div>

                    
                    <?php if(count($model->emails)):?>
                        <div class="widget-view-sub"><i class="icon-file"></i> <b>Email</b><br>
                            <div class="profile-info">
                                <ul class="unstyled">
                                    <?php foreach($model->emails as $email) { ?>
                                        <li><?php echo $email->email ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>


                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <?php
            if ($providerMatricula->totalCount) {
                $gridColumnMatricula = [
                    [
                        'attribute' => 'convenio.nome',
                        'label' => 'Convênio'
                    ],
                    [
                        'attribute' => 'matricula',
                        'label' => 'Matrícula'
                    ],
                    [
                        'attribute' => 'ocupacao',
                        'label' => 'Ocupação'
                    ],
                ];
                echo Gridview::widget([
                    'dataProvider' => $providerMatricula,
                    'pjax' => true,
                    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-matricula']],
                    'panel' => [
                        'type' => GridView::TYPE_WARNING,
                        'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Matrícula'),
                    ],
                    'export' => false,
                    'columns' => $gridColumnMatricula
                ]);
            }
            ?>
        </div>
    </div>


    
    <?php
        $gridColumnResultadoBusca = [
            [
                'attribute' => 'margem',
                'label' => 'Margem'
            ],
            [
                'attribute' => 'margem_reservada',
                'label' => 'Margem Reservada'
            ],
            [
                'attribute' => 'margem_disponivel',
                'label' => 'Margem Disponível'
            ],
            [
                'attribute' => 'margem_cartao',
                'label' => 'Margem Cartão'
            ],
            [
                'attribute' => 'margem_cartao_reservada',
                'label' => 'Cartão Reservado'
            ],
            [
                'attribute' => 'margem_cartao_disponivel',
                'label' => 'Cartão Disponível'
            ],
            [
                'attribute' => 'margem_beneficio',
                'label' => 'Benefício'
            ],
            [
                'attribute' => 'margem_beneficio_reservada',
                'label' => 'Benefício Reservado'
            ],
            [
                'attribute' => 'margem_beneficio_disponivel',
                'label' => 'Benefício Disponível'
            ],
        ];
            
        foreach($model->matriculas as $matricula): 
            $resultadoBusca = ResultadoBusca::find()->where(['matricula_id' => $matricula->id])->orderBy('id DESC')->limit(1)->all();
            $providerResultadoBusca = new \yii\data\ArrayDataProvider([
                'allModels' => $resultadoBusca,
            ]);
    ?>

    <br>
    
    <div class="row">
        <div class="col-lg-12">
            <?php
            if ($providerResultadoBusca->totalCount) {
                echo Gridview::widget([
                    'dataProvider' => $providerResultadoBusca,
                    'pjax' => true,
                    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-resultado-busca-'.$matricula->matricula]],
                    'panel' => [
                        'type' => GridView::TYPE_WARNING,
                        'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Margem Matrícula '.$matricula->matricula),
                    ],
                    'export' => false,
                    'columns' => $gridColumnResultadoBusca
                ]);
            }
            ?>
        </div>
    </div>

    
    
    <?php  endforeach; ?>






    <?php
        $gridColumnContrato = [
            [
                'attribute' => 'descricao',
                'label' => 'Descrição'
            ],
            [
                'attribute' => 'valor_credito',
                'label' => 'Crédito'
            ],
            [
                'attribute' => 'valor_parcela',
                'label' => 'Parcela'
            ],
            [
                'attribute' => 'qtd_parcela',
                'label' => 'Qtd Parcelas'
            ],
            [
                'attribute' => 'qtd_parcela',
                'label' => 'Qtd Parcelas'
            ],
            [
                'attribute' => 'juros_anual',
                'label' => 'Juros Anual'
            ],
            [
                'attribute' => 'juros_mensal',
                'label' => 'Juros Mensal'
            ],
            [
                'attribute' => 'nome_verba',
                'label' => 'Instituição'
            ],
            [
                'attribute' => 'parcela_paga',
                'label' => 'Parcelas Pagas'
            ],
            [
                'attribute' => 'parcela_pagar',
                'label' => 'Parcelas Restantes'
            ],
            [
                'attribute' => 'status_contrato',
                'label' => 'Status'
            ],
        ];
            
        foreach($model->matriculas as $matricula): 
            $arrResultadoBusca = ResultadoBusca::find()->select(['id'])->where(['matricula_id' => $matricula->id])->asArray()->orderBy('id DESC')->one();

            $contrato = Contrato::find()->where(['resultado_busca_id' => $arrResultadoBusca])->orderBy('id DESC')->all();

            $providerContrato = new \yii\data\ArrayDataProvider([
                'allModels' => $contrato,
                'pagination' => [
                    'pageSize' => 5
                ]
            ]);
    ?>

    <br>
    
    <div class="row">
        <div class="col-lg-12">
            <?php
            if ($providerContrato->totalCount) {
                echo Gridview::widget([
                    'dataProvider' => $providerContrato,
                    'pjax' => true,
                    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-contrato-'.$matricula->matricula]],
                    'panel' => [
                        'type' => GridView::TYPE_WARNING,
                        'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Contratos Matrícula '.$matricula->matricula),
                    ],
                    'export' => [
                        'fontAwesome' => false
                    ],
                    'columns' => $gridColumnContrato
                ]);
            }
            ?>
        </div>
    </div>

    
    
    <?php  endforeach; ?>






    

    <div class="row">
        <?php
        if ($providerTelefone->totalCount) {
            $gridColumnTelefone = [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'telefone',
                'ddd',
                'ranking',
                'is_whats',
            ];
            echo Gridview::widget([
                'dataProvider' => $providerTelefone,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-telefone']],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Telefone'),
                ],
                'export' => false,
                'columns' => $gridColumnTelefone
            ]);
        }
        ?>

    </div>
</div>