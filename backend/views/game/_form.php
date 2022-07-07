<?php

use kartik\grid\GridView;
use mix8872\yiiFiles\widgets\FilesWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Game */
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
                <div class="col-8">
                    <?= $form->field($model, 'is_active')->checkbox(['checked' => $model->isNewRecord ? true : null]) ?>
                </div>
                <div class="col-4">
                    <?= $form->field($model, 'order')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'winners_count')->textInput() ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, "image")->widget(FilesWidget::class, []) ?>
                </div>
            </div>
            <?php if (!$model->isNewRecord): ?>
                <div class="row">
                    <div class="page-title-box">
                        <div class="col-12">
                            <h4 class="page-title">Туры игры</h4>
                        </div>
                        <div class="col-12">
                            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-plus']) . ' Добавить тур', ['round-create', 'game' => $model->id], [
                                'class' => 'btn btn-info',
                            ]) ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <?php Pjax::begin(['id' => 'grid-sortable-container']); ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => [
                                'class' => 'js-grid-sortable',
                                'data-sort' => Url::to(['sort-round']),
                            ],
                            'rowOptions' => function ($model, $key, $index) {
                                return [
                                    'key' => $key,
                                    'index' => $index,
                                    'data-id' => $model->id
                                ];
                            },
                            'columns' => [
                                ['class' => 'kartik\grid\SerialColumn'],
                                [
                                    'attribute' => 'image',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        return ($image = $model->image) ? Html::img($image->url) : '';
                                    }
                                ],
                                'answer',
                                'answer_description',
                                'time',
                                'order',
                                [
                                    'class' => 'kartik\grid\ActionColumn',
                                    'template' => '{sort} {update} {delete}',
                                    'urlCreator' => function ($action, $model, $key, $index) {
                                        if ($action === 'update') {
                                            return Url::to(['round-update', 'id' => $model->id]);
                                        }
                                        if ($action === 'delete') {
                                            return Url::to(['round-delete', 'id' => $model->id]);
                                        }
                                    },
                                    'buttons' => [
                                        'sort' => function ($url, $model, $key) {
                                            return Html::tag('i', '', ['class' => 'fas fa-sort js-handle', 'title' => 'Сортировка']);
                                        }
                                    ]
                                ],
                            ],
                        ]) ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

