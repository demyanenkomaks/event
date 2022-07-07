<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $models common\models\UserDopColumn */
/* @var $form yii\widgets\ActiveForm */

DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => $models[0],
    'formId' => 'form-id',
    'formFields' => [
        'key',
        'title',
        'required',
        'registration',
        'registration_sort',
        'pars_column',
    ],
]);

?>

<div class="row container-items m-t-15">

    <?php foreach ($models as $index => $model): ?>
        <div class="item col-md-12 item">
            <?php
            if (!$model->isNewRecord) {
                echo Html::activeHiddenInput($model, "[{$index}]id");
            }
            ?>
            <div class="row p-t-5">
                <div class="col-md-2">
                    <?= $form->field($model, "[{$index}]key")->textInput(['disabled' => true]) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, "[{$index}]title")->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-2 mt-4">
                    <?= $form->field($model, "[{$index}]required")->checkbox([]) ?>
                </div>
                <div class="col-md-2 mt-4">
                    <?= $form->field($model, "[{$index}]registration")->checkbox([]) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, "[{$index}]registration_sort")->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, "[{$index}]pars_column")->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php DynamicFormWidget::end(); ?>
