<?php

/* @var $this yii\web\View */
/* @var $model common\models\UserDopColumn */

$this->title = 'Редактирвоание поля: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Настройка полей пользователя', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-dop-column-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
