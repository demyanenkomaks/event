<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\mailing\models\SendEmailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рассылка E-mail';
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
                    [
                        'attribute' => 'created_at',
                        'filter' => false,
                        'format' => 'datetime'
                    ],
                    'email',
                    'user_id',
                    'template_id',
                    'theme',
                    //'html:ntext',
                    [
                        'attribute' => 'sended_at',
                        'filter' => false,
                        'format' => 'datetime'
                    ],
                    'error',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{view}',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
