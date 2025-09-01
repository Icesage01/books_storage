<?php

namespace src\Infrastructure\Database\Repository;

use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Book\BookModel;

class BookRepository extends AbstractRepository implements BookRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(BookModel::class);
    }
    
    /**
     * @return BookModel[]
     */
    public function findByTitle(string $title): array
    {
        return $this->getQuery()
            ->where(['like', 'title', $title])
            ->all();
    }
    
    /**
     * @return BookModel[]
     */
    public function findByAuthor(int $authorId): array
    {
        return $this->getQuery()
            ->joinWith('authorList')
            ->where(['author.id' => $authorId])
            ->all();
    }
    
    /**
     * @return BookModel[]
     */
    public function findByGenre(string $genre): array
    {
        return $this->findBy(['genre' => $genre]);
    }
    
    /**
     * @return BookModel[]
     */
    public function findAvailableBooks(): array
    {
        return $this->findBy(['isAvailable' => true]);
    }
    
    /**
     * @return BookModel[]
     */
    public function searchByText(string $text): array
    {
        return $this->getQuery()
            ->where(['or',
                ['like', 'title', $text],
                ['like', 'description', $text]
            ])
            ->all();
    }
    
    /**
     * @return BookModel|null
     */
    public function findByIsbn(string $isbn): ?BookModel
    {
        return $this->findOneBy(['isbn' => $isbn]);
    }
    
}
