<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_dop_column}}`.
 */
class m220209_112516_createUserDopColumnTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_dop_column}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull()->unique(),
            'title' => $this->string()->notNull(),
            'deleteConfig' => $this->boolean()->defaultValue(1),
            'type' => "ENUM('string', 'email', 'password', 'select')",
            'select_list' => $this->string(),
            'required' => $this->boolean(),
            'registration' => $this->boolean(),
            'registration_sort' => $this->integer(),
            'pars_column' => $this->integer(),
        ]);

        $this->addColumn('{{%user}}', 'dop_column', $this->json());

        Yii::$app->db->createCommand()
            ->batchInsert('{{%user_dop_column}}', ['key', 'title', 'deleteConfig', 'type', 'required', 'registration'], [
                ['login', 'Логин', 0, 'string', 1, 0],
                ['email', 'E-mail', 0, 'email', 1, 0],
                ['password', 'Пароль', 0, 'password', 1, 0],
                ['name', 'Имя', 0, 'string', 1, 0],
                ['second_name', 'Отчество', 0, 'string', 0, 0],
                ['surname', 'Фамилия', 0, 'string', 0, 0],
                ['phone', 'Телефон', 0, 'string', 0, 0],
            ])->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_dop_column}}');

        $this->dropColumn('{{%user}}', 'dop_column');
    }
}
