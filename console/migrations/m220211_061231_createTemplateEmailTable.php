<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%template_email}}`.
 */
class m220211_061231_createTemplateEmailTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template_email}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'theme' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
        ]);

        $this->batchInsert('template_email', ['code', 'title', 'theme', 'text'], [
            ['confirmationEmail', 'Подтверждение email', 'Подтверждение email', '<p><span style="background-color:transparent; color:rgb(0, 0, 0); font-family:arial; font-size:11pt">Ссылка, чтобы подтвердить email&nbsp; {{linkConfirmationEmail}}</span></p>'],
            ['resetPassword', 'Сброс пароля', 'Сброс пароля', '<p><span style="background-color:transparent; color:rgb(0, 0, 0); font-family:arial; font-size:11pt">Ссылка, чтобы сбросить пароль&nbsp; {{linkResetPassword}}</span></p>'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%template_email}}');
    }
}
