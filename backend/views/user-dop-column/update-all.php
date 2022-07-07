<?php

/* @var $this yii\web\View */
/* @var $model common\models\UserDopColumn */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирвоание полей пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Настройка полей пользователя', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-dop-column-update">

    <?php $form = ActiveForm::begin(['id' => 'form-id']); ?>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="row">
                    <div class="col-12">
                        <h4 class="page-title"><?= Html::encode($this->title) ?></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-chevron-left']) . ' Назад', ['index'], ['class' => 'btn btn-warning mt-2']) ?>
                        <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-save']) . ' Сохранить', ['class' => 'btn btn-success ml-2 mt-2']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?= $this->render('_form_update_all', ['form' => $form, 'models' => $models]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
