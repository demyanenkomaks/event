<?php

/* @var $this yii\web\View */
/* @var $model common\models\Speaker */

$this->title = 'Редактирование спирека: ' . $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Спикеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="speaker-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
