<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Convenio */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Convenio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="convenio-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Convenio'.' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        'nome',
        'descricao:ntext',
        'sigla',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    
    <div class="row">
<?php
if($providerBusca->totalCount){
    $gridColumnBusca = [
        ['class' => 'yii\grid\SerialColumn'],
            'id',
            'nome',
            'dt_criacao',
                ];
    echo Gridview::widget([
        'dataProvider' => $providerBusca,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-busca']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Busca'),
        ],
        'export' => false,
        'columns' => $gridColumnBusca
    ]);
}
?>

    </div>
    
    <div class="row">
<?php
if($providerLoginConvenio->totalCount){
    $gridColumnLoginConvenio = [
        ['class' => 'yii\grid\SerialColumn'],
            'id',
            'usuario',
            'senha',
            'tipo_acesso',
                ];
    echo Gridview::widget([
        'dataProvider' => $providerLoginConvenio,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-login-convenio']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Login Convenio'),
        ],
        'export' => false,
        'columns' => $gridColumnLoginConvenio
    ]);
}
?>

    </div>
    
    <div class="row">
<?php
if($providerMatricula->totalCount){
    $gridColumnMatricula = [
        ['class' => 'yii\grid\SerialColumn'],
            'id',
                        [
                'attribute' => 'cliente.id',
                'label' => 'Cliente'
            ],
            'matricula',
            'ocupacao',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerMatricula,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-matricula']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Matricula'),
        ],
        'export' => false,
        'columns' => $gridColumnMatricula
    ]);
}
?>

    </div>
</div>
