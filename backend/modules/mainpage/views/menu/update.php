<?php

use common\models\MenuMain;

/* @var $this yii\web\View */
/* @var $model common\models\MenuMain */

$this->title = 'Редактирование пункта меню: ' . $model->name;
$this->params['breadcrumbs'][] = [
    'label' => $model->menu === MenuMain::MENU_FOOTER ? 'Меню для подвала (footer)' : 'Меню для шапки (header)',
    'url' => ['index', 'menu' => $model->menu]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-main-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
