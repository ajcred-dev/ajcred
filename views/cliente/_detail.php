<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */

?>
<div class="cliente-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        'nome',
        'cpf',
        'situacao_cpf',
        'data_nascimento',
        'sexo',
        'nome_mae',
        'renda',
        'data_obito',
        'observacao:ntext',
        'novo_campo',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>