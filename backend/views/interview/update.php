<?php

/* @var $this yii\web\View */
/* @var $model common\models\Interview */
/* @var $modelsOptions common\models\InterviewOption */

$this->title = 'Редактирвоание опроса: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsOptions' => $modelsOptions,
    ]) ?>

</div>
