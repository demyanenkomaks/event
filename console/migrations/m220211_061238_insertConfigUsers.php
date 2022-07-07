<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m220211_061238_insertConfigUsers
 */
class m220211_061238_insertConfigUsers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert(
            '{{%config_tab}}',
            ['title', 'order'],
            [
                ['Настройки пользователя', 100],
            ]
        );

        $configTab = (new Query())->select('id')->from('{{%config_tab}}')->where(['title' => 'Настройки пользователя'])->one();

        $this->batchInsert(
            '{{%config}}',
            ['group', 'key', 'name', 'type', 'position', 'readonly', 'protected', 'tabId', 'value'],
            [
                ['Авторизация', 'authorizationField', 'Подпись поля (Логин/E-mail)', 'string', 100, '0', '1', $configTab['id'], 'Логин/E-mail'],
                ['Авторизация', 'authorizationPassword', 'Авторизация с паролем', 'boolean', 100, '0', '1', $configTab['id'], '1'],

                ['Общие', 'userSignature', 'Подпись пользователя', 'string', 100, '0', '1', $configTab['id'], '{name} {surname:1}.'],
                ['Общие', 'confirmationEmail', 'Нужно подтверждение почты (Код шаблона confirmationEmail)', 'boolean', 100, '0', '1', $configTab['id'], '1'],
                ['Общие', 'resetPassword', 'Можно сбросить пароль (Код шаблона resetPassword)', 'boolean', 100, '0', '1', $configTab['id'], '1'],
                ['Общие', 'tokenLifetime', 'Время жизни токена (мин)', 'string', 100, '0', '1', $configTab['id'], '60'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%config}}', [
            'key' => ['authorizationField', 'authorizationPassword', 'userSignature', 'confirmationEmail', 'resetPassword']
        ]);

        $this->delete('{{%config_tab}}', [
            'title' => ['Настройки пользователя']
        ]);
    }

}
