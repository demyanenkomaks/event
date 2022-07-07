<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%interview_option}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%interview}}`
 */
class m220316_123958_createInterviewOptionTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%interview_option}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'is_active' => $this->tinyInteger()->defaultValue(0),
            'order' => $this->integer()->notNull(),
            'interview_id' => $this->integer()->notNull(),
            'option' => $this->text()->notNull(),
        ]);

        // creates index for column `interview_id`
        $this->createIndex(
            '{{%idx-interview_option-interview_id}}',
            '{{%interview_option}}',
            'interview_id'
        );

        // add foreign key for table `{{%interview}}`
        $this->addForeignKey(
            '{{%fk-interview_option-interview_id}}',
            '{{%interview_option}}',
            'interview_id',
            '{{%interview}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%interview}}`
        $this->dropForeignKey(
            '{{%fk-interview_option-interview_id}}',
            '{{%interview_option}}'
        );

        // drops index for column `interview_id`
        $this->dropIndex(
            '{{%idx-interview_option-interview_id}}',
            '{{%interview_option}}'
        );

        $this->dropTable('{{%interview_option}}');
    }
}
