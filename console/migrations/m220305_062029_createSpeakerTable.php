<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%speaker}}`.
 */
class m220305_062029_createSpeakerTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%speaker}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'is_active' => $this->tinyInteger()->defaultValue(0),
            'fio' => $this->text()->notNull(),
            'description' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%speaker}}');
    }
}
