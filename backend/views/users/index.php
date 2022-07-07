<?php

use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    ['class' => 'kartik\grid\SerialColumn'],
    [
        'class' => '\kartik\grid\CheckboxColumn',
        'checkboxOptions' => function ($model) {
            return [
                'value' => $model->id,
                'class' => 'js-batch-checkbox'
            ];
        },
        'rowSelectedClass' => 'info',
    ],

    'email:email',
    'login',
    'signature',
    'name',
    'second_name',
    'surname',
    'phone',
    [
        'attribute' => 'is_tester',
        'filter' => ['нет', 'да'],
        'value' => function ($model) {
            return $model->is_tester ? 'Да' : 'Нет';
        }
    ],
    [
        'attribute' => 'is_moderator',
        'filter' => ['нет', 'да'],
        'value' => function ($model) {
            return $model->is_moderator ? 'Да' : 'Нет';
        }
    ],
    [
        'attribute' => 'is_verified',
        'filter' => ['нет', 'да'],
        'value' => function ($model) {
            return $model->is_verified ? 'Да' : 'Нет';
        }
    ],
    [
        'attribute' => 'is_active',
        'filter' => ['нет', 'да'],
        'value' => function ($model) {
            return $model->is_active ? 'Да' : 'Нет';
        }
    ],
    [
        'attribute' => 'updated_at',
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'updatedAtFrom',
            'attribute2' => 'updatedAtBefore',
            'options' => ['class' => 'form-control'],
            'options2' => ['class' => 'form-control'],
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '',
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy',
                'autoclose' => true,
            ],

        ]),
        'format' => 'datetime'
    ],
    [
        'attribute' => 'registered_at',
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'registeredAtFrom',
            'attribute2' => 'registeredAtBefore',
            'options' => ['class' => 'form-control'],
            'options2' => ['class' => 'form-control'],
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '',
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy',
                'autoclose' => true,
            ],

        ]),
        'format' => 'datetime'
    ],
    [
        'attribute' => 'authorized_at',
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'authorizedAtFrom',
            'attribute2' => 'authorizedAtBefore',
            'options' => ['class' => 'form-control'],
            'options2' => ['class' => 'form-control'],
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '',
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy',
                'autoclose' => true,
            ],

        ]),
        'format' => 'datetime'
    ],
    [
        'attribute' => 'action_at',
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'actionAtFrom',
            'attribute2' => 'actionAtBefore',
            'options' => ['class' => 'form-control'],
            'options2' => ['class' => 'form-control'],
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '',
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy',
                'autoclose' => true,
            ],

        ]),
        'format' => 'datetime'
    ],
    [
        'attribute' => 'random_winner_at',
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'randomWinnerAtFrom',
            'attribute2' => 'randomWinnerAtBefore',
            'options' => ['class' => 'form-control'],
            'options2' => ['class' => 'form-control'],
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '',
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy',
                'autoclose' => true,
            ],

        ]),
        'format' => 'datetime'
    ],
    [
        'attribute' => 'imported_at',
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'importedAtFrom',
            'attribute2' => 'importedAtBefore',
            'options' => ['class' => 'form-control'],
            'options2' => ['class' => 'form-control'],
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '',
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy',
                'autoclose' => true,
            ],

        ]),
        'format' => 'datetime'
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{update} {delete}',
    ],
];

$columnsExport = [
    ['class' => 'kartik\grid\SerialColumn'],
    'email:email',
    'login',
    'signature',
    'name',
    'second_name',
    'surname',
    'phone',
    [
        'attribute' => 'is_tester',
        'value' => function ($model) {
            return $model->is_tester ? 'Да' : 'Нет';
        }
    ],
    [
        'attribute' => 'is_moderator',
        'value' => function ($model) {
            return $model->is_moderator ? 'Да' : 'Нет';
        }
    ],
    [
        'attribute' => 'is_verified',
        'value' => function ($model) {
            return $model->is_verified ? 'Да' : 'Нет';
        }
    ],
    [
        'attribute' => 'is_active',
        'value' => function ($model) {
            return $model->is_active ? 'Да' : 'Нет';
        }
    ],
    [
        'attribute' => 'updated_at',
        'format' => 'datetime'
    ],
    [
        'attribute' => 'registered_at',
        'format' => 'datetime'
    ],
    [
        'attribute' => 'authorized_at',
        'format' => 'datetime'
    ],
    [
        'attribute' => 'action_at',
        'format' => 'datetime'
    ],
    [
        'attribute' => 'random_winner_at',
        'format' => 'datetime'
    ],
    [
        'attribute' => 'imported_at',
        'format' => 'datetime'
    ],
];

if (!empty(Yii::$app->configUserDopColumn->dopColumn)) {
    $dopColumn = Yii::$app->configUserDopColumn->dopColumn;
    foreach ($dopColumn as $item) {
        $columnsExport = array_merge($columnsExport, [
            [
                'label' => $item['title'],
                'value' => function($m) use ($item) {
                    return $m->dop[$item['key']] ?? null;
                }
            ],
        ]);
    }
}

?>
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
                    <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-plus']) . ' Добавить', ['create'], [
                        'class' => 'btn btn-info',
                    ]) ?>

                    <?= Html::button(Html::tag('i', '', ['class' => 'fas fa-download ']) . ' Импорт', [
                        'class' => 'btn btn-success',
                        'data' => [
                            'toggle' => 'modal',
                            'target' => '#import-modal'
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php Pjax::begin(); ?>

<div class="row">
    <div class="col-12">
        <div class="card-box">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => ['heading' => ''],
                'toolbar' => [
                    [
                        'content' =>
                            Html::a('<i class="fas fa-trash-alt"></i>', ['delete'], [
                                'class' => 'btn btn-danger js-batch-action hidden',
                                'title' => 'Удалить выбранные элементы',
                                'data' => [
                                    'message' => 'Удалить выбранные элементы?'
                                ]
                            ]),
                    ],
                    'export' => [
                        'content' => ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $columnsExport,
                            'filename' => 'ExportUsers_' . Yii::$app->formatter->asDatetime('now'),
                        ])
                    ],
                ],
                'columns' => $columns,
            ]) ?>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>

<div id="import-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="import-modal-label" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php $form = ActiveForm::begin(['action' => ['import']]); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="import-modal-label">Импорт пользователей</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p><?= Html::a('Найстроить поля парсинга', ['user-dop-column/update-all'], ['target' => '_blank']) ?></p>
                <p></p>
                <?= $form->field($modelFile, 'file')->fileInput(['multiple' => false, 'accept' => '.csv, text/csv'])->label('Файл (.csv)') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Отмена</button>
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-success waves-effect waves-light']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
