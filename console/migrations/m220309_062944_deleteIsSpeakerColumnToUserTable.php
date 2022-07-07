<?php

use yii\db\Migration;

/**
 * Class m220309_062944_deleteIsSpeakerColumnToUserTable
 */
class m220309_062944_deleteIsSpeakerColumnToUserTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%user}}', 'is_speaker');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%user}}', 'is_speaker', $this->boolean()->notNull()->defaultValue(0));
    }
}
