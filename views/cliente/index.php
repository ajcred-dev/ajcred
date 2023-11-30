<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = 'Cliente';
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="cliente-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Novo Cliente', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="search-form">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <br>
    <?php 
    $gridColumn = [
        'nome',
        'cpf',
        [
            'class' => 'yii\grid\ActionColumn',
        ],
    ]; 
    ?>

    <?php

        // Renders a export dropdown menu
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumn,
            'batchSize' => $dataProvider->getTotalCount()
        ]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'export' => False,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-cliente']],
        'panel' => [
            'type' => GridView::TYPE_WARNING,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
    ]); ?>

</div>
