<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Convenio */

$this->title = 'Novo Convênio';
$this->params['breadcrumbs'][] = ['label' => 'Convenio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="convenio-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
