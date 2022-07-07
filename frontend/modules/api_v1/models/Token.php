<?php

namespace frontend\modules\api_v1\models;

use common\classes\Dop;
use common\models\User;
use Yii;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "token".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $user_id
 * @property string $token
 * @property string $expired_at
 * @property string $expired
 *
 * @property User $user
 */
class Token extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $expired;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'expired_at', 'expired'], 'safe'],
            [['user_id', 'token', 'expired_at'], 'required'],
            [['user_id'], 'integer'],
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique'],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'token' => 'Token',
            'expired_at' => 'Expired At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function generateToken() {
        $this->token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function prolongationTimeToken() {
        $tokenLifetime = (int)trim(Yii::$app->config->tokenLifetime) * 60;
        $expire = Dop::dateTime(time() + $tokenLifetime);

        $this->expired_at = $expire;
        $this->expired = date(DATE_RFC3339, strtotime(Yii::$app->formatter->asDateTime($expire)));

        $user = $this->user;
        $user->action_at = Dop::dateTime();
        $user->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($model = self::find()->where(['and', ['token' => $token], ['>', 'expired_at', Dop::dateTime()]])->with(['user'])->one()) {
            $model->prolongationTimeToken();
            $model->save(false);
        }

        return $model;
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return User::findOne(['id' => $id, 'is_active' => User::ACTIVE_ACTIVE]);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }

    public static function auth($user)
    {
        if (!$token = Token::find()->where(['user_id' => $user->id])->one()) {
            $token = new Token();
            $token->user_id = $user->id;
        }

        $token->generateToken();
        $token->prolongationTimeToken();

        if ($token->save()) {
            $user->authorized_at = Dop::dateTime();
            $user->save(false);

            return $token;
        }

        return null;
    }
}
