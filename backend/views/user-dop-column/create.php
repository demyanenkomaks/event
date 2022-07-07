<?php

/* @var $this yii\web\View */
/* @var $model common\models\UserDopColumn */

$this->title = 'Добавление поля';
$this->params['breadcrumbs'][] = ['label' => 'Настройка полей пользователя', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-dop-column-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
