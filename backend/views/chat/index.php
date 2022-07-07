<?php

use common\models\Chat;
use common\models\Speaker;
use kartik\select2\Select2;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Модерация чата';
$this->params['breadcrumbs'][] = $this->title;

\backend\assets\ChatAsset::register($this);
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

<div class="row">
    <div class="col-12">
        <div class="card-box">
            <?php Pjax::begin(['id' => 'pjax-content', 'timeout' => false, 'enablePushState' => false]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'rowOptions' => function ($model, $key, $index, $grid) {
                $wer = 245;
                    return [
                        'data' => [
                            'id-message' => $model['id'],
                            'message-response' => $model['response']['message'] ?? '',
                            'id-speaker' => $model['speaker_id'] ?? '',
                        ]
                    ];
                },
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'attribute' => 'created_at',
                        'filter' => false,
                        'format' => 'datetime'
                    ],
                    [
                        'attribute' => 'user_id',
                        'filter' => false,
                        'format' => 'raw',
                        'value' => function ($model) {
                            return !empty($model['user']) ? $model['user']['signature'] . (!empty($model['user']['moderator']) ? ' ' . Html::tag('span', 'Модератор', ['class' => 'badge badge-info']) : '') : '';
                        }
                    ],
                    [
                        'attribute' => 'message',
                        'format' => 'ntext',
                        'contentOptions' => function ($model, $key, $index, $column) {
                            return ['class' => 'js-text-message'];
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => Chat::$statusArray,
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<span class="badge badge-' . Chat::$statusColors[$model->status] . '">' . Chat::$statusArray[$model->status] . '</span>';
                        }
                    ],
                    [
                        'label' => 'Модерация',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model['status'] === Chat::STATUS_NEW) {
                                return '<a href="javascript:void(0);" class="js-take-in-work-message text-warning p-1" style="padding: 2px;" title="Взять в работу"><span class="fas fa-play"></span></a>';
                            } else {
                                $approvedMessage = ($model['status'] !== Chat::STATUS_APPROVED ? '<a href="javascript:void(0);" class="js-approved-message text-success" style="padding: 2px;" title="Одобрить сообщение"><span class="fas fa-check-circle"></span></a>' : '');
                                $rejectedMessage = ($model['status'] !== Chat::STATUS_REJECTED ? '<a href="javascript:void(0);" class="js-rejected-message text-danger" style="padding: 2px;" title="Отклонить сообщение"><span class="fas fa-minus-circle"></span></a>' : '');
                                $messageResponse = '<a href="javascript:void(0);" class="js-message-response text-primary" style="padding: 2px;" title="Ответить на сообщение"><span class="fas fa-comment-alt"></span></a>';
                                $questionSpeaker = '<a href="javascript:void(0);" class="js-question-speaker text-primary" style="padding: 2px;" title="Отметить вопрос спикеру"><span class="far fa-question-circle"></span></a>';

                                return $approvedMessage . $rejectedMessage . $messageResponse . $questionSpeaker;
                            }
                        }
                    ],
                ],
            ]) ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php Modal::begin(['id' => 'js-modal-response', 'title' => '<h4>Ответить на сообщение</h4>']); ?>
<input type="hidden" class="js-id-message-response">
<input type="hidden" class="js-id-response">
<div class="form-group">
    <p><b>Сообщение</b></p>
    <p class="js-text-message-form-response"></p>
    <label for="js-textarea-response">Ответ</label>
    <textarea id="js-textarea-response" class="form-control" rows="5" placeholder="Ответ"></textarea>
</div>
<div class="form-group">
    <?= Html::submitButton('Ответить', ['class' => 'btn btn-success js-button-form-response']) ?>
</div>
<?php Modal::end(); ?>


<?php Modal::begin(['id' => 'js-modal-speaker', 'title' => '<h4>Отметить спикера</h4>']); ?>
<p><b>Сообщение</b></p>
<p class="js-text-message-form-speaker"></p>
<input type="hidden" class="js-id-message-speaker">
<div class="form-group">
    <label for="id-speaker">Спикер</label>
    <?= Select2::widget([
        'name' => 'idSpeaker',
        'data' => Speaker::getList(),
        'options' => [
            'id' => 'id-speaker',
            'class' => 'js-id-speaker',
            'placeholder' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>
</div>
<div class="form-group">
    <?= Html::submitButton('Отметить', ['class' => 'btn btn-success js-button-form-speaker']) ?>
</div>
<?php Modal::end(); ?>
