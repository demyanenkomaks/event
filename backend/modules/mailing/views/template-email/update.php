<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TemplateEmail */

$this->title = 'Редактирование шаблона: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны E-mail', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-email-update">

    <?= $this->render('_form', [
        'model' => $model,
        'variables' => $variables,
    ]) ?>

</div>
