<div class="form-group" id="add-endereco">
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
    'formName' => 'Endereco',
    'checkboxColumn' => false,
    'actionColumn' => false,
    'attributeDefaults' => [
        'type' => TabularForm::INPUT_TEXT,
    ],
    'attributes' => [
        'id' => ['type' => TabularForm::INPUT_HIDDEN],
        'endereco' => ['type' => TabularForm::INPUT_TEXT],
        'numero' => ['type' => TabularForm::INPUT_TEXT],
        'complemento' => ['type' => TabularForm::INPUT_TEXT],
        'bairro' => ['type' => TabularForm::INPUT_TEXT],
        'cidade' => ['type' => TabularForm::INPUT_TEXT],
        'uf' => ['type' => TabularForm::INPUT_TEXT],
        'cep' => ['type' => TabularForm::INPUT_TEXT],
        'del' => [
            'type' => 'raw',
            'label' => '',
            'value' => function($model, $key) {
                return
                    Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                    Html::a('<i class="glyphicon glyphicon-trash"></i>', '#', ['title' =>  'Delete', 'onClick' => 'delRowEndereco(' . $key . '); return false;', 'id' => 'endereco-del-btn']);
            },
        ],
    ],
    'gridSettings' => [
        'panel' => [
            'heading' => false,
            'type' => GridView::TYPE_DEFAULT,
            'before' => false,
            'footer' => false,
            'after' => Html::button('<i class="glyphicon glyphicon-plus"></i>' . 'Add Endereco', ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRowEndereco()']),
        ]
    ]
]);
echo  "    </div>\n\n";
?>

