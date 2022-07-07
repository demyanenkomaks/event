<?php

use yii\db\Migration;

/**
 * Class m220309_064621_updateColumnTable
 */
class m220309_064621_updateColumnTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%send_email}}', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->alterColumn('{{%send_email}}', 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        $this->alterColumn('{{%user}}', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->alterColumn('{{%user}}', 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        $this->alterColumn('{{%token}}', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->alterColumn('{{%token}}', 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

        $this->addColumn('{{%template_email}}', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn('{{%template_email}}', 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%send_email}}', 'created_at', $this->timestamp()->defaultValue(null));
        $this->alterColumn('{{%send_email}}', 'updated_at', $this->timestamp()->defaultValue(null));
        $this->alterColumn('{{%user}}', 'created_at', $this->timestamp()->defaultValue(null));
        $this->alterColumn('{{%user}}', 'updated_at', $this->timestamp()->defaultValue(null));
        $this->alterColumn('{{%token}}', 'created_at', $this->timestamp()->defaultValue(null));
        $this->alterColumn('{{%token}}', 'updated_at', $this->timestamp()->defaultValue(null));

        $this->dropColumn('{{%template_email}}', 'created_at');
        $this->dropColumn('{{%template_email}}', 'updated_at');
    }
}
