<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<div class="cliente-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'arquivo')->fileInput()->label('') ?>
    
    <br>
    
    <div class="form-group">
        <?= Html::submitButton('Importar', ['class' =>  'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
