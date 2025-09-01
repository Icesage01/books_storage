<?php

namespace src\Models;

use DateTime;
use src\Behaviour\TimestampBehaviour;
use src\Domain\Book\BookAuthorModel;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $middleName
 * @property bool $isActive
 * @property DateTime $createdAt
 * @property DateTime $updatedAt
 */
class AuthorModel extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%author}}';
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehaviour::class,
            ],
        ];
    }

    public function getBookList(): ActiveQuery
    {
        return $this->hasMany(BookAuthorModel::class, ['authorId' => 'id']);
    }
}
