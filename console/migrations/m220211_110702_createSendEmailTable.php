<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%send_email}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%template_email}}`
 */
class m220211_110702_createSendEmailTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%send_email}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultValue(null),
            'updated_at' => $this->timestamp()->defaultValue(null),
            'email' => $this->string(),
            'user_id' => $this->integer(),
            'template_id' => $this->integer(),
            'theme' => $this->string(),
            'html' => $this->text(),
            'sended_at' => $this->timestamp()->defaultValue(null),
            'error' => $this->text(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-send_email-user_id}}',
            '{{%send_email}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-send_email-user_id}}',
            '{{%send_email}}',
            'user_id',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        // creates index for column `template_id`
        $this->createIndex(
            '{{%idx-send_email-template_id}}',
            '{{%send_email}}',
            'template_id'
        );

        // add foreign key for table `{{%template_email}}`
        $this->addForeignKey(
            '{{%fk-send_email-template_id}}',
            '{{%send_email}}',
            'template_id',
            '{{%template_email}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-send_email-user_id}}',
            '{{%send_email}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-send_email-user_id}}',
            '{{%send_email}}'
        );

        // drops foreign key for table `{{%template_email}}`
        $this->dropForeignKey(
            '{{%fk-send_email-template_id}}',
            '{{%send_email}}'
        );

        // drops index for column `template_id`
        $this->dropIndex(
            '{{%idx-send_email-template_id}}',
            '{{%send_email}}'
        );

        $this->dropTable('{{%send_email}}');
    }
}
