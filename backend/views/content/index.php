<?php

use kartik\datetime\DateTimePicker;
use mihaildev\ckeditor\CKEditor;
use mix8872\yiiFiles\widgets\FilesWidget;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Управление Контент Мероприятия';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'row']]); ?>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-header">
                    <h2 class="text-center">Контент мероприятия</h2>
                </div>
                <div class="form-group float-right">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <?= $form->field($models['titleEvent'], '[titleEvent]value')
                            ->textInput()
                            ->label($models['titleEvent']['title']) ?>

                        <?= $form->field($models['startStringEvent'], '[startStringEvent]value')
                            ->textInput()
                            ->label($models['startStringEvent']['title']) ?>

                        <?= $form->field($models['startEvent'], '[startEvent]value')
                            ->widget(DateTimePicker::class, [])
                            ->label($models['startEvent']['title']) ?>

                        <?= $form->field($models['linkIframe'], '[linkIframe]value')
                            ->textInput()
                            ->label($models['linkIframe']['title']) ?>
                    </div>
                    <div class="col-6">
                        <?= $form->field($models['descriptionEvent'], '[descriptionEvent]value')->widget(CKEditor::class, [
                            'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
                                'allowedContent' => true,
                                'fullPage' => false,
                                'forcePasteAsPlainText' => true,
                                'toolbar' => [
                                    ['Source', 'codemirror'],
                                    ['PasteText', '-', 'Undo', 'Redo'],
                                    ['Replace', 'SelectAll'],
                                    ['Format', 'FontSize'],
                                    ['Bold', 'Italic', 'Underline', 'TextColor'],
                                    ['RemoveFormat', 'Blockquote', 'HorizontalRule'],
                                    ['NumberedList', 'BulletedList'],
                                    ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                                    ['Link', 'Unlink'],
                                    ['Maximize', 'ShowBlocks'],
                                    ['Image'],
                                ],
                                'inline' => false,
                            ]),
                        ])->label($models['descriptionEvent']['title']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <?= $form->field($models['logo'], '[logo]image')
                            ->widget(FilesWidget::class, [])
                            ->label($models['logo']['title']) ?>
                    </div>
                    <div class="col-6">
                        <?= $form->field($models['imageKvEvent'], '[imageKvEvent]image')
                            ->widget(FilesWidget::class, [])
                            ->label($models['imageKvEvent']['title']) ?>
                    </div>
                    <div class="col-6">
                        <?= $form->field($models['privacyPolicy'], '[privacyPolicy]file')
                            ->widget(FilesWidget::class, [])
                            ->label($models['privacyPolicy']['title']) ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-header">
                    <h2 class="text-center">Meta Tags</h2>
                </div>
                <div class="form-group float-right">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <?= $form->field($models['titleMeta'], '[titleMeta]value')
                            ->textInput()
                            ->label($models['titleMeta']['title']) ?>
                    </div>
                    <div class="col-6">
                        <?= $form->field($models['descriptionMeta'], '[descriptionMeta]value')
                            ->textInput()
                            ->label($models['descriptionMeta']['title']) ?>
                    </div>
                    <div class="col-6">
                        <?= $form->field($models['favicon'], '[favicon]image')
                            ->widget(FilesWidget::class, [])
                            ->label($models['favicon']['title']) ?>
                    </div>
                    <div class="col-6">
                        <?= $form->field($models['imageMeta'], '[imageMeta]image')
                            ->widget(FilesWidget::class, [])
                            ->label($models['imageMeta']['title']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
