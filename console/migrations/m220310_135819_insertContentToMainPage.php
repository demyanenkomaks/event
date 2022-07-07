<?php

use yii\db\Migration;

/**
 * Class m220310_135819_insertContentToMainPage
 */
class m220310_135819_insertContentToMainPage extends Migration
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
                ['linkIframe', 'Ссылка на плеер трансляции', 'string', null],
                ['privacyPolicy', 'Политика конфиденциальности', 'file', null],
                ['favicon', 'Favicon', 'image', null],
                ['titleMeta', 'title meta tags', 'string', null],
                ['descriptionMeta', 'description meta tags', 'string', null],
                ['imageMeta', 'image meta tags', 'image', null],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%content}}', [
            'key' => ['linkIframe', 'privacyPolicy', 'favicon', 'titleMeta', 'descriptionMeta', 'imageMeta']
        ]);
    }
}
