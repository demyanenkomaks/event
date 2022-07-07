<?php

/* @var $this yii\web\View */
/* @var $model common\models\GameRound */

$this->title = 'Добавление раунда игры';
$this->params['breadcrumbs'][] = ['label' => 'Игры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Игра', 'url' => ['update', 'id' => $model->game_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-round-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
