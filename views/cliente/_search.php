<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClienteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-cliente-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'placeholder' => 'Nome']) ?>

    <?= $form->field($model, 'cpf')->textInput(['maxlength' => true, 'placeholder' => 'Cpf']) ?>

    <?= $form->field($model, 'convenio_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\Convenio::find()->orderBy('id')->asArray()->all(), 'id', 'nome'),
        'options' => ['placeholder' => 'Escolha um convênio'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label("Convênio"); ?>

    <?php /* echo $form->field($model, 'sexo')->textInput(['maxlength' => true, 'placeholder' => 'Sexo']) */ ?>

    <?php /* echo $form->field($model, 'nome_mae')->textInput(['maxlength' => true, 'placeholder' => 'Nome Mae']) */ ?>

    <?php /* echo $form->field($model, 'renda')->textInput(['placeholder' => 'Renda']) */ ?>

    <?php /* echo $form->field($model, 'data_obito')->textInput(['maxlength' => true, 'placeholder' => 'Data Obito']) */ ?>

    <?php /* echo $form->field($model, 'observacao')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'novo_campo')->textInput(['maxlength' => true, 'placeholder' => 'Novo Campo']) */ ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton('Procurar', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Limpar Busca', ['index'], ['class' => 'btn btn-default']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
