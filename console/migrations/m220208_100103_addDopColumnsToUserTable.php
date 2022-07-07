<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m220208_100103_addDopColumnsToUserTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'is_tester', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{%user}}', 'is_moderator', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{%user}}', 'is_speaker', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{%user}}', 'is_verified', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{%user}}', 'is_active', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{%user}}', 'name', $this->string()->notNull());
        $this->addColumn('{{%user}}', 'second_name', $this->string());
        $this->addColumn('{{%user}}', 'surname', $this->string());
        $this->addColumn('{{%user}}', 'login', $this->string()->unique());
        $this->addColumn('{{%user}}', 'phone', $this->bigInteger());
        $this->addColumn('{{%user}}', 'registered_at', $this->timestamp()->defaultValue(null));
        $this->addColumn('{{%user}}', 'authorized_at', $this->timestamp()->defaultValue(null));
        $this->addColumn('{{%user}}', 'action_at', $this->timestamp()->defaultValue(null));
        $this->addColumn('{{%user}}', 'random_winner_at', $this->timestamp()->defaultValue(null));
        $this->addColumn('{{%user}}', 'imported_at', $this->timestamp()->defaultValue(null));
        $this->addColumn('{{%user}}', 'email_confirm_token', $this->string()->unique()->after('email'));

        $this->alterColumn('{{%user}}', 'email', $this->string()->null());
        $this->alterColumn('{{%user}}', 'created_at', $this->timestamp()->defaultValue(null));
        $this->alterColumn('{{%user}}', 'updated_at', $this->timestamp()->defaultValue(null));
        $this->alterColumn('{{%user}}', 'username', $this->string()->null());
        $this->alterColumn('{{%user}}', 'status', $this->smallInteger()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'is_tester');
        $this->dropColumn('{{%user}}', 'is_moderator');
        $this->dropColumn('{{%user}}', 'is_speaker');
        $this->dropColumn('{{%user}}', 'is_verified');
        $this->dropColumn('{{%user}}', 'is_active');
        $this->dropColumn('{{%user}}', 'name');
        $this->dropColumn('{{%user}}', 'second_name');
        $this->dropColumn('{{%user}}', 'surname');
        $this->dropColumn('{{%user}}', 'login');
        $this->dropColumn('{{%user}}', 'phone');
        $this->dropColumn('{{%user}}', 'registered_at');
        $this->dropColumn('{{%user}}', 'authorized_at');
        $this->dropColumn('{{%user}}', 'action_at');
        $this->dropColumn('{{%user}}', 'random_winner_at');
        $this->dropColumn('{{%user}}', 'imported_at');
        $this->dropColumn('{{%user}}', 'email_confirm_token');

        $this->alterColumn('{{%user}}', 'email', $this->string()->notNull());
        $this->alterColumn('{{%user}}', 'created_at', $this->integer()->notNull());
        $this->alterColumn('{{%user}}', 'updated_at', $this->integer()->notNull());
        $this->alterColumn('{{%user}}', 'username', $this->string()->notNull()->unique());
        $this->alterColumn('{{%user}}', 'status', $this->smallInteger()->notNull()->defaultValue(10));
    }
}
