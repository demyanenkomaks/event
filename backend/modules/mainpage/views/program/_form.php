<?php

use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Program */
/* @var $form yii\widgets\ActiveForm */
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
                <div class="col">
                    <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-plus']), ['create'], [
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
                <div class="col-4">
                    <?= $form->field($model, 'is_active')->checkbox(['checked' => $model->isNewRecord ? true : null]) ?>
                </div>
            </div>
            <div class="row">

                <div class="col-4">
                    <?= $form->field($model, 'time_from')->widget(TimePicker::class, [
                        'pluginOptions' => [
                            'defaultTime' => false,
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 5,
                        ]
                    ]) ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'time_before')->widget(TimePicker::class, [
                        'pluginOptions' => [
                            'defaultTime' => false,
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 5,
                        ]
                    ]) ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'order')->textInput() ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'speakerArray')->widget(Select2::class, [
                        'data' => \common\models\Speaker::getList(),
                        'options' => ['prompt' => ''],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => true,
                            'autocomplete' => 'off'
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

