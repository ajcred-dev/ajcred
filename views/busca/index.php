<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap5\Button;

$this->title = 'Busca';
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="busca-index">

    <h1><?= Html::encode($this->title) ?></h1>

    
<?php 
    $gridColumn = [
        'id',
        'nome',
        'dt_criacao',
        [
            'label' => 'Quantitativo',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getResultadoBuscas()->count();
            }
        ],
        [
            'label' => 'Exportar Resultado',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a('Gerar Relatório', ['api-resultado-busca/resultado-busca','busca_id' => $model->id], ['class' => 'btn btn-warning','data-pjax' => 0, 'target' => "_blank"]);
            }
        ]
    ]; 
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-busca']],
        'panel' => [
            'type' => GridView::TYPE_WARNING,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
        'export' => false,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_PDF => false
                ]
            ]) ,
        ],
    ]); ?>

</div>
