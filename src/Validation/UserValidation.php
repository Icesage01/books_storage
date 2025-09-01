<?php

namespace src\Validation;

use src\Models\UserModel;
use yii\base\Model;
use yii\web\Request;

class UserValidation extends Model
{
    public string $username;
    public string $email;
    public string $password;
    public int $status;

    public function rules(): array
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['username', 'email'], 'unique', 'targetClass' => UserModel::class],
            [['username'], 'string', 'max' => 50],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 100],
            [['status'], 'integer'],
            [['status'], 'default', 'value' => UserModel::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [
                UserModel::STATUS_INACTIVE,
                UserModel::STATUS_ACTIVE,
            ]],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'status' => 'Статус',
        ];
    }

    public static function fromRequest(Request $request): self
    {
        $validation = new self();
        $validation->load($request->post());
        return $validation;
    }
}
