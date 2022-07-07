<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chat}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%chat}}`
 * - `{{%speaker}}`
 */
class m220311_104301_createChatTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chat}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'user_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'status' => $this->tinyInteger()->defaultValue(0),
            'moderator_id' => $this->integer(),
            'message_id' => $this->integer(),
            'speaker_id' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-chat-user_id}}',
            '{{%chat}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-chat-user_id}}',
            '{{%chat}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `moderator_id`
        $this->createIndex(
            '{{%idx-chat-moderator_id}}',
            '{{%chat}}',
            'moderator_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-chat-moderator_id}}',
            '{{%chat}}',
            'moderator_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `message_id`
        $this->createIndex(
            '{{%idx-chat-message_id}}',
            '{{%chat}}',
            'message_id'
        );

        // add foreign key for table `{{%chat}}`
        $this->addForeignKey(
            '{{%fk-chat-message_id}}',
            '{{%chat}}',
            'message_id',
            '{{%chat}}',
            'id',
            'CASCADE'
        );

        // creates index for column `speaker_id`
        $this->createIndex(
            '{{%idx-chat-speaker_id}}',
            '{{%chat}}',
            'speaker_id'
        );

        // add foreign key for table `{{%speaker}}`
        $this->addForeignKey(
            '{{%fk-chat-speaker_id}}',
            '{{%chat}}',
            'speaker_id',
            '{{%speaker}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-chat-user_id}}',
            '{{%chat}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-chat-user_id}}',
            '{{%chat}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-chat-moderator_id}}',
            '{{%chat}}'
        );

        // drops index for column `moderator_id`
        $this->dropIndex(
            '{{%idx-chat-moderator_id}}',
            '{{%chat}}'
        );

        // drops foreign key for table `{{%chat}}`
        $this->dropForeignKey(
            '{{%fk-chat-message_id}}',
            '{{%chat}}'
        );

        // drops index for column `message_id`
        $this->dropIndex(
            '{{%idx-chat-message_id}}',
            '{{%chat}}'
        );

        // drops foreign key for table `{{%speaker}}`
        $this->dropForeignKey(
            '{{%fk-chat-speaker_id}}',
            '{{%chat}}'
        );

        // drops index for column `speaker_id`
        $this->dropIndex(
            '{{%idx-chat-speaker_id}}',
            '{{%chat}}'
        );

        $this->dropTable('{{%chat}}');
    }
}
