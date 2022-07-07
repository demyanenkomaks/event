<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m220211_134221_insertConfigSmtp
 */
class m220211_134221_insertConfigSmtp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $configTab = (new Query())->select('id')->from('{{%config_tab}}')->where(['title' => 'SMTP'])->one();

        $this->batchInsert(
            '{{%config}}',
            ['group', 'key', 'name', 'type', 'position', 'readonly', 'protected', 'tabId', 'value'],
            [
                ['SMTP', 'from_sender_email', 'Почта отправителя', 'string', 100, '0', '1', $configTab['id'], ''],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%config}}', [
            'key' => ['from_sender_email']
        ]);
    }
}
