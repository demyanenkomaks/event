<?php

use common\models\UserDopColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserDopColumn */
/* @var $form yii\widgets\ActiveForm */

$js = <<<JS
$('#userdopcolumn-type').on('change', function() {
  if (this.value == 'select') {
    $('.js-select_list').show();
  } else {
    $('.js-select_list').hide();
  }
});
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
                <div class="col-12">
                    <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-chevron-left']) . ' Назад', ['index'], ['class' => 'btn btn-warning mt-2']) ?>
                    <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-save']) . ($model->isNewRecord ? ' Добавить' : ' Сохранить'), ['class' => 'btn btn-success ml-2 mt-2']) ?>
                    <?php if (!$model->isNewRecord && !!$model->deleteConfig): ?>
                        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-times']) . ' Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger ml-2 mt-2',
                            'data-confirm' => 'Удалить элемент?'
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'key')->textInput(['maxlength' => true, 'disabled' => !$model->isNewRecord]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'type')->dropDownList(UserDopColumn::$typeList, ['disabled' => !$model->isNewRecord]) ?>
                </div>
                <div class="col-6 js-select_list" style="<?= $model->type !== 'select' ? 'display: none' : '' ?>">
                    <?= $form->field($model, 'select_list')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 mt-4">
                    <?= $form->field($model, 'required')->checkbox() ?>
                </div>
                <div class="col-2 mt-4">
                    <?= $form->field($model, 'registration')->checkbox() ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'registration_sort')->textInput() ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'pars_column')->textInput() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

