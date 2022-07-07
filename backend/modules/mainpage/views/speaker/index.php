<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\mainpage\models\SpeakerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Спикеры';
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

<?php Pjax::begin(); ?>
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    'id',
                    'fio:ntext',
                    'description:ntext',
                    [
                        'attribute' => 'is_active',
                        'filter' => ['нет', 'да'],
                        'value' => function ($model) {
                            return $model->is_active ? 'Да' : 'Нет';
                        }
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} {delete}',
                    ]
                ],
            ]) ?>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
