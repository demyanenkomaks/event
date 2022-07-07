<?php

/* @var $this yii\web\View */
/* @var $model common\models\Program */

$this->title = 'Редактирование программы: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Программа', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
