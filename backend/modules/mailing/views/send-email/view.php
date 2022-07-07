<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SendEmail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Рассылка E-mail', 'url' => ['index']];
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
                    <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-chevron-left']) . ' Назад', ['index'], ['class' => 'btn btn-warning mt-1']) ?>
                </div>
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
                        'created_at',
                        'updated_at',
                        'email',
                        'user_id',
                        'template_id',
                        'theme',
                        'html:html',
                        'sended_at',
                        'error:ntext',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
