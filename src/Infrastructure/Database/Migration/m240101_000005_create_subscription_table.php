<?php

use yii\db\Migration;

/**
 * Создание таблицы подписок на новые книги авторов
 */
class m240101_000005_create_subscription_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'authorId' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notNull(),
            'email' => $this->string(100)->null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-subscription-author',
            '{{%subscription}}',
            'authorId',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('idx-subscription-authorId', '{{%subscription}}', 'authorId');
        $this->createIndex('idx-subscription-phone', '{{%subscription}}', 'phone');
        $this->createIndex('idx-subscription-status', '{{%subscription}}', 'status');
        $this->createIndex('idx-subscription-unique', '{{%subscription}}', ['authorId', 'phone'], true);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%subscription}}');
    }
}
