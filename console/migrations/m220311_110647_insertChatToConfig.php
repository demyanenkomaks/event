<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m220311_110647_insertChatToConfig
 */
class m220311_110647_insertChatToConfig extends Migration
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
                ['Интерактивные блоки', 100],
            ]
        );

        $configTab = (new Query())->select('id')->from('{{%config_tab}}')->where(['title' => 'Интерактивные блоки'])->one();

        $this->batchInsert(
            '{{%config}}',
            ['group', 'key', 'name', 'type', 'position', 'readonly', 'protected', 'tabId', 'value'],
            [
                ['Чат', 'chatBlock', 'Чат блок', 'number', 100, '1', '1', $configTab['id'], '0'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%config}}', [
            'key' => ['chatBlock']
        ]);

        $this->delete('{{%config_tab}}', [
            'title' => ['Интерактивные блоки']
        ]);
    }
}
