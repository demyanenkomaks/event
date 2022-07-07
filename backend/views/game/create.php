<?php

/* @var $this yii\web\View */
/* @var $model common\models\Game */

$this->title = 'Добавление игры';
$this->params['breadcrumbs'][] = ['label' => 'Игры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
