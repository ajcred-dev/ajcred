<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<div class="cliente-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton('Importar', ['class' =>  'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
