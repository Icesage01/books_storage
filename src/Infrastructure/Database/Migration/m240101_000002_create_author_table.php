<?php

use yii\db\Migration;

/**
 * Создание таблицы авторов
 */
class m240101_000002_create_author_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'firstName' => $this->string(100)->notNull(),
            'lastName' => $this->string(100)->notNull(),
            'middleName' => $this->string(100)->null(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('idx-author-lastName', '{{%author}}', 'lastName');
        $this->createIndex('idx-author-firstName', '{{%author}}', 'firstName');
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%author}}');
    }
}
