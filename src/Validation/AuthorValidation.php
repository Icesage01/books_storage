<?php

namespace src\Validation;

use yii\base\Model;
use yii\web\Request;

class AuthorValidation extends Model
{
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $middleName = null;
    public ?bool $isActive = null;

    public function rules(): array
    {
        return [
            [['firstName', 'lastName'], 'required'],
            [['firstName', 'lastName', 'middleName'], 'string', 'max' => 100],
            [['isActive'], 'boolean'],
            [['isActive'], 'default', 'value' => true],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'firstName' => 'Имя',
            'lastName' => 'Фамилия',
            'middleName' => 'Отчество',
            'isActive' => 'Активен',
        ];
    }

    public static function fromRequest(Request $request): self
    {
        $validation = new self();
        $validation->load($request->post());
        return $validation;
    }
}
