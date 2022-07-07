<?php

/* @var $this yii\web\View */
/* @var $model common\models\QuizQuestion */

$this->title = 'Добавление вопроса квиза';
$this->params['breadcrumbs'][] = ['label' => 'Квизы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Квиз', 'url' => ['update', 'id' => $model->quiz_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quiz-question-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsOptions' => $modelsOptions,
    ]) ?>

</div>
