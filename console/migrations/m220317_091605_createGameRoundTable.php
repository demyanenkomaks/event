<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%game_round}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%game}}`
 */
class m220317_091605_createGameRoundTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%game_round}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'is_active' => $this->tinyInteger()->defaultValue(0),
            'order' => $this->integer()->notNull(),
            'game_id' => $this->integer()->notNull(),
            'time' => $this->integer()->notNull(),
            'answer' => $this->string()->notNull(),
            'answer_description' => $this->text()->notNull(),
        ]);

        // creates index for column `game_id`
        $this->createIndex(
            '{{%idx-game_round-game_id}}',
            '{{%game_round}}',
            'game_id'
        );

        // add foreign key for table `{{%game}}`
        $this->addForeignKey(
            '{{%fk-game_round-game_id}}',
            '{{%game_round}}',
            'game_id',
            '{{%game}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%game}}`
        $this->dropForeignKey(
            '{{%fk-game_round-game_id}}',
            '{{%game_round}}'
        );

        // drops index for column `game_id`
        $this->dropIndex(
            '{{%idx-game_round-game_id}}',
            '{{%game_round}}'
        );

        $this->dropTable('{{%game_round}}');
    }
}
