<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m220211_123221_insertConfigTestig
 */
class m220211_123221_insertConfigTestig extends Migration
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
                ['Тестирование', 100],
            ]
        );

        $configTab = (new Query())->select('id')->from('{{%config_tab}}')->where(['title' => 'Тестирование'])->one();

        $this->batchInsert(
            '{{%config}}',
            ['group', 'key', 'name', 'type', 'position', 'readonly', 'protected', 'tabId', 'value'],
            [
                ['Общие', 'autoSendEmail', 'Автоматическая рассылка на почту (вкл.)', 'boolean', 100, '0', '1', $configTab['id'], '0'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%config}}', [
            'key' => ['autoSendEmail']
        ]);

        $this->delete('{{%config_tab}}', [
            'title' => ['Тестирование']
        ]);
    }
}
