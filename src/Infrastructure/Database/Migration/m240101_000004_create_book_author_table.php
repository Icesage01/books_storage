<?php

use yii\db\Migration;

/**
 * Создание связующей таблицы книг и авторов
 */
class m240101_000004_create_book_author_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%book_author}}', [
            'id' => $this->primaryKey(),
            'bookId' => $this->integer()->notNull(),
            'authorId' => $this->integer()->notNull(),
            'createdAt' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-book_author-book',
            '{{%book_author}}',
            'bookId',
            '{{%book}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-book_author-author',
            '{{%book_author}}',
            'authorId',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('idx-book_author-bookId', '{{%book_author}}', 'bookId');
        $this->createIndex('idx-book_author-authorId', '{{%book_author}}', 'authorId');
        $this->createIndex('idx-book_author-unique', '{{%book_author}}', ['bookId', 'authorId'], true);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%book_author}}');
    }
}
