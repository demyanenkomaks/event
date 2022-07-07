<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%next_event}}`.
 */
class m220305_085944_createNextEventTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%next_event}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'is_active' => $this->tinyInteger()->defaultValue(0),
            'order' => $this->integer()->notNull(),
            'name' => $this->text()->notNull(),
            'when' => $this->string()->notNull(),
            'link' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%next_event}}');
    }
}
