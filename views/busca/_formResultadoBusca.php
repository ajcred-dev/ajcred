<div class="form-group" id="add-resultado-busca">
<?php
use kartik\grid\GridView;
use kartik\builder\TabularForm;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\Pjax;

$dataProvider = new ArrayDataProvider([
    'allModels' => $row,
    'pagination' => [
        'pageSize' => -1
    ]
]);
echo TabularForm::widget([
    'dataProvider' => $dataProvider,
    'formName' => 'ResultadoBusca',
    'checkboxColumn' => false,
    'actionColumn' => false,
    'attributeDefaults' => [
        'type' => TabularForm::INPUT_TEXT,
    ],
    'attributes' => [
        'id' => ['type' => TabularForm::INPUT_HIDDEN],
        'margem' => ['type' => TabularForm::INPUT_TEXT],
        'margem_cartao' => ['type' => TabularForm::INPUT_TEXT],
        'matricula_id' => [
            'label' => 'Matricula',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Matricula::find()->orderBy('matricula')->asArray()->all(), 'id', 'matricula'),
                'options' => ['placeholder' => 'Choose Matricula'],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'del' => [
            'type' => 'raw',
            'label' => '',
            'value' => function($model, $key) {
                return
                    Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                    Html::a('<i class="glyphicon glyphicon-trash"></i>', '#', ['title' =>  'Delete', 'onClick' => 'delRowResultadoBusca(' . $key . '); return false;', 'id' => 'resultado-busca-del-btn']);
            },
        ],
    ],
    'gridSettings' => [
        'panel' => [
            'heading' => false,
            'type' => GridView::TYPE_DEFAULT,
            'before' => false,
            'footer' => false,
            'after' => Html::button('<i class="glyphicon glyphicon-plus"></i>' . 'Add Resultado Busca', ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRowResultadoBusca()']),
        ]
    ]
]);
echo  "    </div>\n\n";
?>

