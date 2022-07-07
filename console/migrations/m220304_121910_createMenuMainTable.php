<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu_main}}`.
 */
class m220304_121910_createMenuMainTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menu_main}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'is_active' => $this->tinyInteger()->defaultValue(0),
            'order' => $this->integer()->notNull(),
            'menu' => "ENUM('h', 'f')",
            'name' => $this->string()->notNull(),
            'link' => $this->string(),
            'targetBlank' => $this->tinyInteger()->defaultValue(0),
            'type' => $this->tinyInteger()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%menu_main}}');
    }
}
