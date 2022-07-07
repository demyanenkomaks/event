<?php

/* @var $this yii\web\View */
/* @var $model common\models\Quiz */

$this->title = 'Добавление квиза';
$this->params['breadcrumbs'][] = ['label' => 'Квизы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quiz-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
