<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LoginConvenio */

$this->title = 'Update Login Convenio: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Login Convenio', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="login-convenio-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
