<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TemplateEmail */

$this->title = 'Добалвение шаблона';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны E-mail', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-email-create">

    <?= $this->render('_form', [
        'model' => $model,
        'variables' => $variables,
    ]) ?>

</div>
