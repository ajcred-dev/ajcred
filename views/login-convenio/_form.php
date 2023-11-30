<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LoginConvenio */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="login-convenio-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true, 'placeholder' => 'Usuario']) ?>

    <?= $form->field($model, 'senha')->textInput(['maxlength' => true, 'placeholder' => 'Senha']) ?>

    <?= $form->field($model, 'tipo_acesso')->textInput(['placeholder' => 'Tipo Acesso']) ?>

    <?= $form->field($model, 'convenio_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\Convenio::find()->orderBy('id')->asArray()->all(), 'id', 'nome'),
        'options' => ['placeholder' => 'Choose Convenio'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Criar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
