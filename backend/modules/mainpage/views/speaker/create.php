<?php

/* @var $this yii\web\View */
/* @var $model common\models\Speaker */

$this->title = 'Добевление спикера';
$this->params['breadcrumbs'][] = ['label' => 'Спикеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="speaker-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
