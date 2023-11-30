<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Busca */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Busca', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="busca-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Busca'.' '. Html::encode($this->title) ?></h2>
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
        'dt_criacao',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    
    <div class="row">
<?php
if($providerResultadoBusca->totalCount){
    $gridColumnResultadoBusca = [
        ['class' => 'yii\grid\SerialColumn'],
            'id',
            'margem',
            'margem_cartao',
                        [
                'attribute' => 'matricula.matricula',
                'label' => 'Matricula'
            ],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerResultadoBusca,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-resultado-busca']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Resultado Busca'),
        ],
        'export' => false,
        'columns' => $gridColumnResultadoBusca
    ]);
}
?>

    </div>
</div>
