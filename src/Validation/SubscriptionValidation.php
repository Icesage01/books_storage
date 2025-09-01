<?php

namespace src\Validation;

use src\Models\SubscriptionModel;
use yii\base\Model;
use yii\web\Request;

class SubscriptionValidation extends Model
{
    public int $authorId;
    public string $phone;
    public ?string $email;
    public int $status;

    public function rules(): array
    {
        return [
            [['authorId', 'phone'], 'required'],
            [['authorId'], 'integer'],
            [['status'], 'integer'],
            [['status'], 'default', 'value' => SubscriptionModel::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [
                SubscriptionModel::STATUS_INACTIVE,
                SubscriptionModel::STATUS_ACTIVE,
            ]],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 100],
            [['authorId', 'phone'], 'unique', 'targetAttribute' => ['authorId', 'phone'], 'targetClass' => \src\Models\SubscriptionModel::class],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'authorId' => 'ID автора',
            'phone' => 'Телефон',
            'email' => 'Email',
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
