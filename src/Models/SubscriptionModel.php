<?php

namespace src\Models;

use DateTime;
use src\Behaviour\TimestampBehaviour;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $authorId ID автора
 * @property string $phone Телефон для SMS уведомлений
 * @property string $email Email для уведомлений
 * @property int $status Статус (0 - неактивна, 1 - активна)
 * @property DateTime $createdAt Дата создания
 * @property DateTime $updatedAt Дата обновления
 */
class SubscriptionModel extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName(): string
    {
        return '{{%subscription}}';
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehaviour::class,
            ],
        ];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(AuthorModel::class, ['id' => 'authorId']);
    }
}
