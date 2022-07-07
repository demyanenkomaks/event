<?php

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TemplateEmail */
/* @var $form yii\widgets\ActiveForm */

$js = <<<JS
!function($) {
    $('.js-ck-insert').on('click', function() {
        CKEDITOR.instances['templateemail-text'].insertText($(this).text());
    });

    var textareaUtm = $('#templateemail-utm');

    $('.js-ck-insert-utm').on('click', function() {
        var value = textareaUtm.val();
        textareaUtm.val(value + $(this).text());
    });
}(jQuery)
JS;
$this->registerJs($js);
?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="row">
                <div class="col-12">
                    <h4 class="page-title"><?= Html::encode($this->title) ?></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-chevron-left']) . ' Назад', ['index'], ['class' => 'btn btn-warning mt-2']) ?>
                    <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-save']) . ($model->isNewRecord ? ' Добавить' : ' Сохранить'), ['class' => 'btn btn-success ml-2 mt-2']) ?>
                    <?php if (!$model->isNewRecord): ?>
                        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-times']) . ' Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger ml-2 mt-2',
                            'data-confirm' => 'Удалить элемент?'
                        ]) ?>
                    <?php endif; ?>
                </div>
                <?php if (!$model->isNewRecord): ?>
                    <div class="col-md-3 col-sm-12">
                        <p class="text-muted mt-3">
                            <?= $model->getAttributeLabel('created_at') . ': ' . Yii::$app->formatter->asDatetime($model->created_at) ?></p>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <p class="text-muted mt-3">
                            <?= $model->getAttributeLabel('updated_at') . ': ' . Yii::$app->formatter->asDatetime($model->updated_at) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <div class="row">
                <div class="col-12">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'readonly' => $model->isNewRecord ? false : true]) ?>
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'text')->widget(CKEditor::class, [
                        'editorOptions' => [
                            'preset' => 'full',
                        ],
                    ]) ?>
                </div>
                <?php if (!empty($variables)): ?>
                    <div class="col-12">
                        <h5>Доступны следующие переменные:</h5>
                        <ul>
                            <?php foreach ($variables as $variable => $desc): ?>
                                <?php if (preg_match('/{{\w}}/u', $variable) !== false): ?>
                                    <li><b class="cursor-pointer js-ck-insert"><?= $variable ?></b> - <?= $desc ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

