<?php

/* @var $this yii\web\View */
/* @var $model common\models\Game */

$this->title = 'Редактирование игры: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Игры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-update">

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
