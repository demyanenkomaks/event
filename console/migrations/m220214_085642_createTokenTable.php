<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%token}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m220214_085642_createTokenTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%token}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultValue(null),
            'updated_at' => $this->timestamp()->defaultValue(null),
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string()->notNull()->unique(),
            'expired_at' => $this->timestamp()->defaultValue(null),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-token-user_id}}',
            '{{%token}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-token-user_id}}',
            '{{%token}}',
            'user_id',
            '{{%user}}',
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
            '{{%fk-token-user_id}}',
            '{{%token}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-token-user_id}}',
            '{{%token}}'
        );

        $this->dropTable('{{%token}}');
    }
}
