<?php

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
        <?= Html::tag('h2', Html::encode($this->title), ['class' => 'pull-left float-left']) ?>
        <div class="form-group float-right pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-chevron-left']) . ' Назад', (Yii::$app->request->referrer ?? ['index']), ['class' => 'btn btn-warning']) ?>
            <?php if (!$model->isNewRecord): ?>
                <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-save']) . ' Обновить', ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-trash']) . ' Удалить', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger', 'data' => [
                    'confirm' => 'Вы действительно хотите удалить пользователя?',
                    'method' => 'post'
                ]]) ?>
            <?php else : ?>
                <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-plus']) . ' Добавить', ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-2 mt-4">
                        <?= $form->field($model, 'is_active')->checkbox() ?>
                    </div>
                    <div class="col-3 mt-4">
                        <?= $form->field($model, 'is_verified')->checkbox() ?>
                    </div>
                    <div class="col-2 mt-4">
                        <?= $form->field($model, 'is_tester')->checkbox() ?>
                    </div>
                    <div class="col-2 mt-4">
                        <?= $form->field($model, 'is_moderator')->checkbox() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-4">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-4">
                        <?php if ($model->isNewRecord): ?>
                            <?= $form->field($model, 'password')->widget(PasswordInput::class, []) ?>
                        <?php else: ?>
                            <?= $form->field($model, 'password')->widget(PasswordInput::class, [])
                                ->passwordInput(['placeholder' => 'Новый пароль (если нужно изменить его)'])
                            ?>
                        <?php endif ?>
                    </div>
                    <div class="col-4">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-4">
                        <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-4">
                        <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-4">
                        <?= $form->field($model, 'phone')->textInput() ?>
                    </div>
                </div>
                <?php if (!empty($dopColumns)): ?>
                    <div class="row">
                        <?php foreach ($dopColumns as $i => $column): ?>
                            <?= Html::activeHiddenInput($column, "[{$i}]key")?>
                            <div class="col-6">
                                <?php if ($column->type === 'select'): ?>
                                    <?= $form->field($column, "[{$i}]value")
                                        ->dropDownList(
                                            array_key_exists($column->value, $column->selectList) ? $column->selectList : array_merge([$column->value => $column->value], $column->selectList),
                                                [
                                                    'value' => $column->value,
                                                    'prompt' => '',
                                                    'required' => !!$column->required
                                                ])
                                        ->label($column->title)
                                    ?>
                                <?php else: ?>
                                    <?= $form->field($column, "[{$i}]value")
                                        ->textInput(['value' => $column->value, 'required' => !!$column->required])
                                        ->label($column->title)
                                    ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!$model->isNewRecord) : ?>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <p>
                            <strong><?= $model->getAttributeLabel('login') ?>:</strong>
                            <span class="m-l-15"><?= $model->login ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('email') ?>:</strong>
                            <span class="m-l-15"><a href="mailto:<?= $model->email ?>"><?= $model->email ?></a></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('name') ?>:</strong>
                            <span class="m-l-15"><?= $model->name ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('second_name') ?>:</strong>
                            <span class="m-l-15"><?= $model->second_name ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('surname') ?>:</strong>
                            <span class="m-l-15"><?= $model->surname ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('phone') ?>:</strong>
                            <span class="m-l-15"><?= $model->phone ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('created_at') ?>:</strong>
                            <span class="m-l-15"><?= Yii::$app->formatter->asDateTime($model->created_at) ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('updated_at') ?>:</strong>
                            <span class="m-l-15"><?= Yii::$app->formatter->asDateTime($model->updated_at) ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('registered_at') ?>:</strong>
                            <span class="m-l-15"><?= Yii::$app->formatter->asDateTime($model->registered_at) ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('authorized_at') ?>:</strong>
                            <span class="m-l-15"><?= Yii::$app->formatter->asDateTime($model->authorized_at) ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('action_at') ?>:</strong>
                            <span class="m-l-15"><?= Yii::$app->formatter->asDateTime($model->action_at) ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('random_winner_at') ?>:</strong>
                            <span class="m-l-15"><?= Yii::$app->formatter->asDateTime($model->random_winner_at) ?></span>
                        </p>
                        <p>
                            <strong><?= $model->getAttributeLabel('imported_at') ?>:</strong>
                            <span class="m-l-15"><?= Yii::$app->formatter->asDateTime($model->imported_at) ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php ActiveForm::end(); ?>
<?php if (!$model->isNewRecord): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3>Права и роли</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-5">
                            Доступные:
                            <input id="search-avaliable" class="form-control"><br>
                            <select id="list-avaliable" class="form-control" multiple size="20" style="width: 100%">
                            </select>
                        </div>
                        <div class="col-lg-1 col-lg-offset-1">
                            <div class="assign-btn-block">
                                <a href="#" id="btn-assign" class="btn btn-success">&gt;&gt;</a><br>
                                <a href="#" id="btn-revoke" class="btn btn-danger">&lt;&lt;</a>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            Назначенные:
                            <input id="search-assigned" class="form-control"><br>
                            <select id="list-assigned" class="form-control" multiple size="20" style="width: 100%">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        \mix8872\useradmin\assets\AdminAsset::register($this);
        $properties = Json::htmlEncode([
            'userId' => $model->id,
            'assignUrl' => Url::to(['assign']),
            'searchUrl' => Url::to(['role-search']),
        ]);
        $js = <<<JS
            yii.admin.initProperties({$properties});
            
            $('#search-avaliable').keydown(function () {
                yii.admin.searchAssignmet('avaliable');
            });
            $('#search-assigned').keydown(function () {
                yii.admin.searchAssignmet('assigned');
            });
            $('#btn-assign').click(function () {
                yii.admin.assign('assign');
                return false;
            });
            $('#btn-revoke').click(function () {
                yii.admin.assign('revoke');
                return false;
            });
            
            yii.admin.searchAssignmet('avaliable', true);
            yii.admin.searchAssignmet('assigned', true);
            
            $('input.avatar-input').bootstrapFileInput();
JS;
        $this->registerJs($js);
        ?>
    </div>
<?php endif; ?>
