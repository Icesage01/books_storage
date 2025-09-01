<?php

namespace src\Models;

use DateTime;
use src\Behaviour\TimestampBehaviour;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title Название
 * @property int $publicationYear Год выпуска
 * @property string $description Описание
 * @property string $isbn ISBN
 * @property string $coverImage Фото главной страницы
 * @property DateTime $createdAt Дата создания
 * @property DateTime $updatedAt Дата обновления
 */
class BookModel extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%book}}';
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehaviour::class,
            ],
        ];
    }

    public function getAuthorList(): ActiveQuery
    {
        return $this->hasMany(BookAuthorModel::class, ['bookId' => 'id']);
    }
}
