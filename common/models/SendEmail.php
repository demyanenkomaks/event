<?php

namespace common\models;

use common\classes\Dop;
use Yii;
use yii\db\Expression;
use Mustache_Engine;

/**
 * This is the model class for table "send_email".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $email
 * @property int $user_id
 * @property int $template_id
 * @property string $theme
 * @property string $html
 * @property string $sended_at
 * @property string $error
 *
 * @property TemplateEmail $template
 * @property User $user
 */
class SendEmail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'send_email';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'sended_at'], 'safe'],
            [['user_id', 'template_id'], 'integer'],
            [['email', 'html', 'error'], 'string'],
            [['theme'], 'string', 'max' => 255],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => TemplateEmail::class, 'targetAttribute' => ['template_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Добавлен',
            'updated_at' => 'Отредактирован',
            'email' => 'E-mail',
            'user_id' => 'Пользователь',
            'template_id' => 'Шаблон',
            'theme' => 'Тема',
            'html' => 'Html',
            'sended_at' => 'Отправлен',
            'error' => 'Error',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert && Yii::$app->config->autoSendEmail == '1') {
            self::send();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(TemplateEmail::class, ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function sendTemplate($idUser, $codeTemplate)
    {
        if (is_int($codeTemplate)) {
            $template = TemplateEmail::findOne(['id' => $codeTemplate]);
        } else {
            $template = TemplateEmail::findOne(['code' => $codeTemplate]);
        }

        $user = User::findOne(['id' => $idUser]);

        if (empty($template) || empty($user) || empty($user->email)) {
            return false;
        }

        $m = new Mustache_Engine(['escape' => function ($value) {return $value;}]);
        $attributes = $user->attributes;

        $model = new self();
        $model->email = $user->email;
        $model->user_id = $user->id;
        $model->template_id = $template->id;
        $model->theme = $m->render($template->theme, $attributes);
        $model->html = $m->render($template->text, $attributes);

        return $model->save();
    }

    public function send()
    {
        $send = true;
        Yii::$app->config->initMailer();

        try {
            $mailer = Yii::$app->mailer
                ->compose()
                ->setFrom(Yii::$app->config->from_sender_email)
                ->setTo($this->email)
                ->setSubject($this->theme)
                ->setTextBody(strip_tags($this->html))
                ->setHtmlBody($this->html);

            if (!$result = $mailer->send()) {
                $this->error = $result;
                $send = false;
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $send = false;
        }

        $this->sended_at = Dop::dateTime();
        $this->save();

        return $send;
    }
}
