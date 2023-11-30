<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Email', 
        'relID' => 'email', 
        'value' => \yii\helpers\Json::encode($model->emails),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Endereco', 
        'relID' => 'endereco', 
        'value' => \yii\helpers\Json::encode($model->enderecos),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Matricula', 
        'relID' => 'matricula', 
        'value' => \yii\helpers\Json::encode($model->matriculas),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Telefone', 
        'relID' => 'telefone', 
        'value' => \yii\helpers\Json::encode($model->telefones),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="cliente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'placeholder' => 'Nome']) ?>

    <?= $form->field($model, 'cpf')->textInput(['maxlength' => true, 'placeholder' => 'CPF']) ?>

    <?= $form->field($model, 'situacao_cpf')->textInput(['maxlength' => true, 'placeholder' => 'Situação Cpf']) ?>

    <?= $form->field($model, 'data_nascimento')->textInput(['maxlength' => true, 'placeholder' => 'Data Nascimento']) ?>

    <?= $form->field($model, 'sexo')->textInput(['maxlength' => true, 'placeholder' => 'Sexo']) ?>

    <?= $form->field($model, 'nome_mae')->textInput(['maxlength' => true, 'placeholder' => 'Nome da Mãe']) ?>

    <?= $form->field($model, 'renda')->textInput(['placeholder' => 'Renda']) ?>

    <?= $form->field($model, 'data_obito')->textInput(['maxlength' => true, 'placeholder' => 'Data Óbito']) ?>

    <?= $form->field($model, 'observacao')->textarea(['rows' => 6]) ?>

    <?php
    $forms = [
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('Email'),
            'content' => $this->render('_formEmail', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->emails),
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('Endereço'),
            'content' => $this->render('_formEndereco', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->enderecos),
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('Matrícula'),
            'content' => $this->render('_formMatricula', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->matriculas),
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('Telefone'),
            'content' => $this->render('_formTelefone', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->telefones),
            ]),
        ],
    ];
    echo kartik\tabs\TabsX::widget([
        'items' => $forms,
        'position' => kartik\tabs\TabsX::POS_ABOVE,
        'encodeLabels' => false,
        'pluginOptions' => [
            'bordered' => true,
            'sideways' => true,
            'enableCache' => false,
        ],
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Novo' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
