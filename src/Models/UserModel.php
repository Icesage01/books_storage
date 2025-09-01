<?php

namespace src\Models;

use DateTime;
use src\Behaviour\TimestampBehaviour;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property int $id
 * @property string $username Логин
 * @property string $email Email
 * @property string $passwordHash Хеш пароля
 * @property string $authKey Ключ авторизации
 * @property string $accessToken Токен доступа
 * @property int $status Статус (0 - неактивен, 10 - активен)
 * @property DateTime $createdAt Дата создания
 * @property DateTime $updatedAt Дата обновления
 */
class UserModel extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;

    public static function tableName(): string
    {
        return '{{%user}}';
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehaviour::class,
            ],
        ];
    }

    public static function findIdentity($id): ?static
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?static
    {
        return static::findOne(['accessToken' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): string
    {
        return $this->authKey ?? '';
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->authKey === $authKey;
    }

    public static function findByUsername(string $username): ?static
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->passwordHash);
    }

    public function setPassword(string $password): void
    {
        $this->passwordHash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey(): void
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    public function generateAccessToken(): void
    {
        $this->accessToken = Yii::$app->security->generateRandomString();
    }
}
