<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TemplateEmail */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны E-mail', 'url' => ['index']];
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
                <div class="col-md-4 col-sm-12">
                    <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-chevron-left']) . ' Назад', ['index'], ['class' => 'btn btn-warning mt-1']) ?>
                    <?= Html::a(Html::tag('i', '', ['class' => 'fas fa-pencil-alt']) . ' Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-info ml-2 mt-1']) ?>
                    <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-times']) . ' Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger ml-2 mt-1',
                        'data' => [
                            'method' => 'post',
                            'confirm' => 'Удалить элемент?'
                        ]
                    ]) ?>
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
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <div class="row">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'code',
                        'title',
                        'theme',
                        'text:html',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
