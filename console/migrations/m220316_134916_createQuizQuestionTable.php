<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quiz_question}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%quiz}}`
 */
class m220316_134916_createQuizQuestionTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quiz_question}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'is_active' => $this->tinyInteger()->defaultValue(0),
            'order' => $this->integer()->notNull(),
            'quiz_id' => $this->integer()->notNull(),
            'question' => $this->text()->notNull(),
            'type' => $this->tinyInteger()->notNull(),
            'time' => $this->integer()->notNull(),
            'point' => $this->integer()->notNull(),
            'answer' => $this->string(),
            'answer_description' => $this->text(),
        ]);

        // creates index for column `quiz_id`
        $this->createIndex(
            '{{%idx-quiz_question-quiz_id}}',
            '{{%quiz_question}}',
            'quiz_id'
        );

        // add foreign key for table `{{%quiz}}`
        $this->addForeignKey(
            '{{%fk-quiz_question-quiz_id}}',
            '{{%quiz_question}}',
            'quiz_id',
            '{{%quiz}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%quiz}}`
        $this->dropForeignKey(
            '{{%fk-quiz_question-quiz_id}}',
            '{{%quiz_question}}'
        );

        // drops index for column `quiz_id`
        $this->dropIndex(
            '{{%idx-quiz_question-quiz_id}}',
            '{{%quiz_question}}'
        );

        $this->dropTable('{{%quiz_question}}');
    }
}
