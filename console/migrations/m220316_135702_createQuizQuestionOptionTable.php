<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quiz_question_option}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%quiz_question}}`
 */
class m220316_135702_createQuizQuestionOptionTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quiz_question_option}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'quiz_question_id' => $this->integer()->notNull(),
            'option' => $this->string(),
            'is_active' => $this->tinyInteger()->defaultValue(0),
            'order' => $this->integer()->notNull(),
            'is_correct' => $this->tinyInteger()->defaultValue(0),
        ]);

        // creates index for column `quiz_question_id`
        $this->createIndex(
            '{{%idx-quiz_question_option-quiz_question_id}}',
            '{{%quiz_question_option}}',
            'quiz_question_id'
        );

        // add foreign key for table `{{%quiz_question}}`
        $this->addForeignKey(
            '{{%fk-quiz_question_option-quiz_question_id}}',
            '{{%quiz_question_option}}',
            'quiz_question_id',
            '{{%quiz_question}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%quiz_question}}`
        $this->dropForeignKey(
            '{{%fk-quiz_question_option-quiz_question_id}}',
            '{{%quiz_question_option}}'
        );

        // drops index for column `quiz_question_id`
        $this->dropIndex(
            '{{%idx-quiz_question_option-quiz_question_id}}',
            '{{%quiz_question_option}}'
        );

        $this->dropTable('{{%quiz_question_option}}');
    }
}
