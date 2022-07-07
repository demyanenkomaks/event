<?php

use common\models\UserDopColumn;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserDopColumnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройка полей пользователя';
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
                    <?= Html::a('Редактирвоание всех полей', ['update-all'], [
                        'class' => 'btn btn-info',
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
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    'key',
                    'title',
                    [
                        'attribute' => 'type',
                        'filter' => UserDopColumn::$typeList,
                        'value' => function ($model) {
                            return UserDopColumn::$typeList[$model->type];
                        }
                    ],
                    'select_list',
                    [
                        'attribute' => 'required',
                        'filter' => ['нет', 'да'],
                        'value' => function ($model) {
                            return $model->required ? 'Да' : 'Нет';
                        }
                    ],
                    [
                        'attribute' => 'registration',
                        'filter' => ['нет', 'да'],
                        'value' => function ($model) {
                            return $model->registration ? 'Да' : 'Нет';
                        }
                    ],
                    'registration_sort',
                    'pars_column',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'visibleButtons' => [
                            'delete' => function ($model) {
                                return !!$model->deleteConfig;
                            },
                        ]
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
