<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LoginConvenio */

$this->title = 'Create Login Convenio';
$this->params['breadcrumbs'][] = ['label' => 'Login Convenio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-convenio-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
