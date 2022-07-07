<?php

use common\models\QuizQuestion;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\QuizQuestion */
/* @var $form yii\widgets\ActiveForm */

$js = <<<'EOD'
$(document).ready(function() {
    $('#quizquestion-type').change(function() {
        if ($(this).val() === '1') {
            $('.js-answer').hide();
            $('.js-options').show();
        } else if ($(this).val() === '2') {
            $('.js-answer').show();
            $('.js-options').hide();
        }
    });
});
EOD;
$this->registerJs($js);
?>

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
                <div class="col">
                    <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-plus']), ['question-create', 'quiz' => $model->quiz_id], [
                        'class' => 'btn btn-sm btn-info float-right mt-2',
                        'title' => 'Добавить новую запись'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <div class="row">
                <div class="col-8">
                    <?= $form->field($model, 'is_active')->checkbox(['checked' => $model->isNewRecord ? true : null]) ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'order')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?= $form->field($model, 'question')->textarea(['rows' => 1]) ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'type')->dropDownList(QuizQuestion::$typesArray) ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'time')->textInput() ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'point')->textInput() ?>
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'answer_description')->textarea(['rows' => 2]) ?>
                </div>
                <div class="col-12 js-answer" <?= in_array($model->type, [1]) ? 'style="display: none;"' : ''?>>
                    <?= $form->field($model, 'answer')->textInput(['maxlength' => true])->label('Ответ (через ";", что принимать за правильный)') ?>
                </div>
            </div>
            <div class="row js-options" <?= in_array($model->type, [2]) ? 'style="display: none;"' : ''?>>
                <div class="col-12">
                    <?= $this->render('_options', ['form' => $form, 'modelsOptions' => $modelsOptions]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

