<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%interview}}`.
 */
class m220316_121839_createInterviewTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%interview}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'name' => $this->string()->notNull(),
            'question' => $this->text()->notNull(),
            'is_active' => $this->tinyInteger()->defaultValue(0),
            'order' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%interview}}');
    }
}
