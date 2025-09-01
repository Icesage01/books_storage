<?php

namespace src\Models;

use DateTime;
use src\Behaviour\TimestampBehaviour;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $bookId ID книги
 * @property int $authorId ID автора
 * @property DateTime $createdAt Дата создания
 */
class BookAuthorModel extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%book_author}}';
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehaviour::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function getBook(): ActiveQuery
    {
        return $this->hasOne(BookModel::class, ['id' => 'bookId']);
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(AuthorModel::class, ['id' => 'authorId']);
    }
}
