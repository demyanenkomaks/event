<?php

/* @var $this yii\web\View */
/* @var $model common\models\NextEvent */

$this->title = 'Редактирование следующего мероприятия: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Следующие мероприятия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="next-event-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
