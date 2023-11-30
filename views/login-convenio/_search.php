<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LoginConvenioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-login-convenio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id')->textInput(['placeholder' => 'Id']) ?>

    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true, 'placeholder' => 'Usuario']) ?>

    <?= $form->field($model, 'senha')->textInput(['maxlength' => true, 'placeholder' => 'Senha']) ?>

    <?= $form->field($model, 'tipo_acesso')->textInput(['placeholder' => 'Tipo Acesso']) ?>

    <?= $form->field($model, 'convenio_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\Convenio::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => 'Choose Convenio'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
