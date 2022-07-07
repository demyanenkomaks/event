<?php

/* @var $this yii\web\View */
/* @var $model common\models\Quiz */

$this->title = 'Редактирование квиза: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Квизы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quiz-update">

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
