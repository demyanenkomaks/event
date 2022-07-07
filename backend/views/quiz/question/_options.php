<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $modelsOptions common\models\QuizQuestionOption */

DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper',
    'widgetBody' => '.container-items',
    'widgetItem' => '.item',
//    'limit' => 10,
    'min' => 0,
    'insertButton' => '.add-item',
    'deleteButton' => '.remove-item',
    'model' => $modelsOptions[0],
    'formId' => 'form-id',
    'formFields' => [
        'is_active',
        'option',
        'is_correct',
    ],
]);

?>
<div class="page-title-box">
    <h4 class="page-title">Варианты</h4>
    <button type="button" class="pull-right btn btn-success add-item"><i class="fa fa-plus"></i>
        Добавить вариант
    </button>
</div>

<div class="row container-items m-t-15">
    <?php foreach ($modelsOptions as $index => $model): ?>
        <div class="item col-md-12">
            <?php
            if (!$model->isNewRecord) {
                echo Html::activeHiddenInput($model, "[{$index}]id");
            }
            ?>
            <div class="row p-t-5">
                <div class="col-md-1 mt-4 text-center vcenter">
                    <i class="fas fa-sort js-handle" style="cursor: move;"></i>
                </div>
                <div class="col-md-1 mt-4">
                    <?= $form->field($model, "[{$index}]is_active")->checkbox() ?>
                </div>
                <div class="col-md-1 mt-4">
                    <?= $form->field($model, "[{$index}]is_correct")->checkbox() ?>
                </div>
                <div class="col-md-8">
                    <?= $form->field($model, "[{$index}]option")->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-1" style="margin-top: 30px">
                    <button type="button" class="btn btn-danger remove-item">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php DynamicFormWidget::end(); ?>
<?php
$js = <<<'EOD'

var fixHelperSortable = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};

$(".container-items").sortable({
    items: ".item",
    cursor: "move",
    opacity: 0.6,
    axis: "y",
    handle: ".js-handle",
    helper: fixHelperSortable,
    update: function(ev){
        $(".dynamicform_wrapper").yiiDynamicForm("updateContainer");
    }
}).disableSelection();

EOD;
$this->registerJs($js);
?>
