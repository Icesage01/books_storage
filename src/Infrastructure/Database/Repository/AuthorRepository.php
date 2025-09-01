<?php

namespace src\Infrastructure\Database\Repository;

use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Author\AuthorModel;

/**
 * @extends AbstractRepository<AuthorModel>
 */
class AuthorRepository extends AbstractRepository implements AuthorRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(AuthorModel::class);
    }
    
    /**
     * @return AuthorModel[]
     */
    public function findByName(string $name): array
    {
        return $this->getQuery()
            ->where(['like', 'name', $name])
            ->all();
    }
    
    /**
     * @return AuthorModel[]
     */
    public function findByLastName(string $lastName): array
    {
        return $this->getQuery()
            ->where(['like', 'lastName', $lastName])
            ->all();
    }
    
    /**
     * @return AuthorModel[]
     */
    public function findByCountry(string $country): array
    {
        return $this->findBy(['country' => $country]);
    }
    
    /**
     * @return AuthorModel[]
     */
    public function findPopularAuthors(int $limit = 10, ?int $year = null): array
    {
        $query = $this->getQuery()
            ->select(['author.*', 'COUNT(book_author.bookId) as bookCount'])
            ->leftJoin('book_author', 'author.id = book_author.authorId')
            ->leftJoin('book', 'book_author.bookId = book.id');
            
        if (!is_null($year)) {
            $query->andWhere(['book.publicationYear' => $year]);
        }
        
        $query->groupBy('author.id')
            ->orderBy(['bookCount' => SORT_DESC])
            ->limit($limit);
        
        return $query->all();
    }

    /**
     * @return array
     */
    public function findPopularAuthorsForReport(int $limit = 10, ?int $year = null): array
    {
        $query = $this->getQuery()
            ->select(['author.*', 'COUNT(book_author.bookId) as bookCount'])
            ->leftJoin('book_author', 'author.id = book_author.authorId')
            ->leftJoin('book', 'book_author.bookId = book.id');
            
        if (!is_null($year)) {
            $query->andWhere(['book.publicationYear' => $year]);
        }
        
        $query->groupBy('author.id')
            ->orderBy(['bookCount' => SORT_DESC])
            ->limit($limit);
        
        return $query->asArray()->all();
    }
    
    /**
     * @return AuthorModel[]
     */
    public function searchByText(string $text): array
    {
        return $this->getQuery()
            ->where(['or',
                ['like', 'name', $text],
                ['like', 'lastName', $text],
                ['like', 'biography', $text]
            ])
            ->all();
    }

    /**
     * @return AuthorModel|null
     */
    public function findById(int $id): ?AuthorModel
    {
        return parent::findById($id);
    }
    
}
