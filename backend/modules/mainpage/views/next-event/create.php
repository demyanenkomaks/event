<?php

/* @var $this yii\web\View */
/* @var $model common\models\NextEvent */

$this->title = 'Добалвение следующего мероприятия';
$this->params['breadcrumbs'][] = ['label' => 'Следующие мероприятия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="next-event-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
