<?php

namespace src\Domain\Book;

use src\Models\BookAuthorModel as BaseBookAuthorModel;
use src\Domain\Author\AuthorModel;
use yii\db\ActiveQuery;

class BookAuthorModel extends BaseBookAuthorModel
{
    public function getBook(): ActiveQuery
    {
        return $this->hasOne(BookModel::class, ['id' => 'bookId']);
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(AuthorModel::class, ['id' => 'authorId']);
    }
}
