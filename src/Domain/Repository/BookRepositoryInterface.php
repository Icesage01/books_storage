<?php

namespace src\Domain\Repository;

use src\Domain\Book\BookModel;

interface BookRepositoryInterface extends RepositoryInterface
{
    /**
     * @return BookModel[]
     */
    public function findByTitle(string $title): array;
    
    /**
     * @return BookModel[]
     */
    public function findByAuthor(int $authorId): array;
    
    /**
     * @return BookModel[]
     */
    public function findByGenre(string $genre): array;
    
    /**
     * @return BookModel[]
     */
    public function findAvailableBooks(): array;
    
    /**
     * @return BookModel[]
     */
    public function searchByText(string $text): array;
}
