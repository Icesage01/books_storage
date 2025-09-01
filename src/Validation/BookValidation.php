<?php

namespace src\Validation;

use yii\base\Model;
use yii\web\Request;

class BookValidation extends Model
{
    public ?string $title = null;
    public ?int $publicationYear = null;
    public ?string $description = null;
    public ?string $isbn = null;
    public ?string $coverImage = null;

    public function rules(): array
    {
        return [
            [['title', 'publicationYear', 'isbn'], 'required'],
            [['publicationYear'], 'integer', 'min' => 1800, 'max' => date('Y')],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['coverImage'], 'string', 'max' => 500],
            [['title', 'isbn'], 'default', 'value' => ''],
            [['publicationYear'], 'default', 'value' => null],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Название',
            'publicationYear' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'coverImage' => 'Обложка',
        ];
    }

    public static function fromRequest(Request $request): self
    {
        $validation = new self();
        $validation->load($request->post());
        return $validation;
    }
}
