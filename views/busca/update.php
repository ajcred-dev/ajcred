<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Busca */

$this->title = 'Update Busca: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Busca', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="busca-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
