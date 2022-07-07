<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InterviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Опросы';
$this->params['breadcrumbs'][] = $this->title;
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
                </div>
            </div>
        </div>
    </div>
</div>

<?php Pjax::begin(['id' => 'grid-sortable-container']); ?>
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'js-grid-sortable',
                    'data-sort' => Url::to(['sort']),
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
                    'name',
                    'question:ntext',
                    [
                        'attribute' => 'is_active',
                        'filter' => ['нет', 'да'],
                        'value' => function ($model) {
                            return $model->is_active ? 'Да' : 'Нет';
                        }
                    ],
                    [
                        'attribute' => 'order',
                        'filter' => false,
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{sort} {update} {delete}',
                        'buttons' => [
                            'sort' => function ($url, $model, $key) {
                                return Html::tag('i', '', ['class' => 'fas fa-sort js-handle', 'title' => 'Сортировка']);
                            }
                        ]
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
