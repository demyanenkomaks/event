<?php

/* @var $this yii\web\View */
/* @var $model common\models\Program */

$this->title = 'Добавление программы';
$this->params['breadcrumbs'][] = ['label' => 'Программа', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
