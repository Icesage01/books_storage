<?php

use yii\db\Migration;

/**
 * Создание таблицы книг
 */
class m240101_000003_create_book_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'publicationYear' => $this->integer()->notNull(),
            'description' => $this->text()->null(),
            'isbn' => $this->string(20)->notNull()->unique(),
            'coverImage' => $this->string(500)->null(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('idx-book-title', '{{%book}}', 'title');
        $this->createIndex('idx-book-publicationYear', '{{%book}}', 'publicationYear');
        $this->createIndex('idx-book-isbn', '{{%book}}', 'isbn');
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%book}}');
    }
}
