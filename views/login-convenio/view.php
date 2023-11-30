<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\LoginConvenio */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Login Convenio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-convenio-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Login Convenio'.' '. Html::encode($this->title) ?></h2>
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
        'usuario',
        'senha',
        'tipo_acesso',
        [
            'attribute' => 'convenio.id',
            'label' => 'Convenio',
        ],
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    <div class="row">
        <h4>Convenio<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnConvenio = [
        'id',
        'nome',
        'descricao',
        'sigla',
    ];
    echo DetailView::widget([
        'model' => $model->convenio,
        'attributes' => $gridColumnConvenio    ]);
    ?>
</div>
