<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Busca */

$this->title = 'Create Busca';
$this->params['breadcrumbs'][] = ['label' => 'Busca', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="busca-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
