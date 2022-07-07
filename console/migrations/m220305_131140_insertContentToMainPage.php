<?php

use yii\db\Migration;

/**
 * Class m220305_131140_insertContentToMainPage
 */
class m220305_131140_insertContentToMainPage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert(
            '{{%content}}',
            ['key', 'title', 'type', 'value'],
            [
                ['logo', 'Логотип', 'image', null],
                ['imageKvEvent', 'Картинка Kv', 'image', null],
                ['titleEvent', 'Название мероприятия', 'string', 'Название мероприятия'],
                ['descriptionEvent', 'Описание мероприятия', 'html', '<p>Описание мероприятия</p><p>&nbsp;</p>'],
                ['startStringEvent', 'Начало мероприятия', 'string', 'Задайте начало мероприятия'],
                ['startEvent', 'Начало открытия мероприятия', 'datetime', null],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%content}}', [
            'key' => ['logo', 'imageKvEvent', 'titleEvent', 'descriptionEvent', 'startStringEvent', 'startEvent']
        ]);
    }
}
