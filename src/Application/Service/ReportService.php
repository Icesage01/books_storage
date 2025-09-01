<?php

namespace src\Application\Service;

use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Repository\BookRepositoryInterface;

class ReportService
{
    private AuthorRepositoryInterface $authorRepository;
    private BookRepositoryInterface $bookRepository;
    
    public function __construct(
        AuthorRepositoryInterface $authorRepository,
        BookRepositoryInterface $bookRepository
    ) {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
    }
    
    /**
     * @param int $year
     * @return array
     */
    public function getTopAuthorsByYear(int $year): array
    {
        $authors = $this->authorRepository->findPopularAuthors(10);
        
        $result = [];
        foreach ($authors as $author) {
            $result[] = [
                'id' => $author->id,
                'firstName' => $author->firstName,
                'lastName' => $author->lastName,
                'middleName' => $author->middleName,
                'bookCount' => $author->bookCount,
                'fullName' => $this->formatAuthorName(
                    $author->lastName,
                    $author->firstName,
                    $author->middleName
                )
            ];
        }
        
        return $result;
    }

    /**
     * @return array
     */
    public function getBooksByYearStatistics(): array
    {
        $books = $this->bookRepository->findAll();
        
        $yearStats = [];
        foreach ($books as $book) {
            $year = $book->publicationYear;
            if (!isset($yearStats[$year])) {
                $yearStats[$year] = 0;
            }
            $yearStats[$year]++;
        }
        
        $result = [];
        foreach ($yearStats as $year => $count) {
            $result[] = [
                'year' => $year,
                'bookCount' => $count
            ];
        }
        
        usort($result, fn($a, $b) => $b['year'] <=> $a['year']);
        
        return $result;
    }

    private function formatAuthorName(string $lastName, string $firstName, ?string $middleName): string
    {
        $fullName = sprintf('%s %s', $lastName, $firstName);
        
        if (!is_null($middleName) && !empty($middleName)) {
            $fullName = sprintf('%s %s', $fullName, $middleName);
        }
        
        return $fullName;
    }
}
