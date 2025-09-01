<?php

namespace src\Domain\Book;

use src\Models\BookModel as BaseBookModel;
use yii\db\ActiveQuery;

class BookModel extends BaseBookModel
{
    public function getFormattedPublicationYear(): string
    {
        return sprintf('Год издания: %d', $this->publicationYear);
    }

    public function isNewBook(): bool
    {
        $currentYear = (int)date('Y');
        return ($currentYear - $this->publicationYear) <= 2;
    }

    public function getShortDescription(): string
    {
        if (is_null($this->description) || empty($this->description)) {
            return 'Описание отсутствует';
        }
        
        $maxLength = 100;
        if (mb_strlen($this->description) <= $maxLength) {
            return $this->description;
        }
        
        return sprintf('%s...', mb_substr($this->description, 0, $maxLength));
    }

    public function hasCover(): bool
    {
        return !is_null($this->coverImage) && !empty($this->coverImage);
    }

    public function getAuthorList(): ActiveQuery
    {
        return $this->hasMany(BookAuthorModel::class, ['bookId' => 'id']);
    }
}
