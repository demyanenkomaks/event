<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%program_speaker}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%program}}`
 * - `{{%speaker}}`
 */
class m220305_070936_createProgramSpeakerTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%program_speaker}}', [
            'program_id' => $this->integer()->notNull(),
            'speaker_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `program_id`
        $this->createIndex(
            '{{%idx-program_speaker-program_id}}',
            '{{%program_speaker}}',
            'program_id'
        );

        // add foreign key for table `{{%program}}`
        $this->addForeignKey(
            '{{%fk-program_speaker-program_id}}',
            '{{%program_speaker}}',
            'program_id',
            '{{%program}}',
            'id',
            'CASCADE'
        );

        // creates index for column `speaker_id`
        $this->createIndex(
            '{{%idx-program_speaker-speaker_id}}',
            '{{%program_speaker}}',
            'speaker_id'
        );

        // add foreign key for table `{{%speaker}}`
        $this->addForeignKey(
            '{{%fk-program_speaker-speaker_id}}',
            '{{%program_speaker}}',
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
        // drops foreign key for table `{{%program}}`
        $this->dropForeignKey(
            '{{%fk-program_speaker-program_id}}',
            '{{%program_speaker}}'
        );

        // drops index for column `program_id`
        $this->dropIndex(
            '{{%idx-program_speaker-program_id}}',
            '{{%program_speaker}}'
        );

        // drops foreign key for table `{{%speaker}}`
        $this->dropForeignKey(
            '{{%fk-program_speaker-speaker_id}}',
            '{{%program_speaker}}'
        );

        // drops index for column `speaker_id`
        $this->dropIndex(
            '{{%idx-program_speaker-speaker_id}}',
            '{{%program_speaker}}'
        );

        $this->dropTable('{{%program_speaker}}');
    }
}
