<?php

namespace src\Application\Command\Handler;

use InvalidArgumentException;
use RuntimeException;
use src\Application\Command\CommandInterface;
use src\Application\Command\UpdateBookCommand;
use src\Domain\Book\BookModel;
use src\Domain\Book\BookAuthorModel;
use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Event\EventDispatcherInterface;
use src\Domain\Event\Book\BookUpdated;

class UpdateBookCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
        private readonly AuthorRepositoryInterface $authorRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    public function handle(CommandInterface $command): bool
    {
        if (!$command instanceof UpdateBookCommand) {
            throw new InvalidArgumentException('Неверный тип команды');
        }

        $book = $this->bookRepository->findById($command->bookId);
        
        if (is_null($book)) {
            throw new RuntimeException(sprintf('Книга с ID %d не найдена', $command->bookId));
        }

        $validation = $command->getValidation();
        
        $book->title = $validation->title;
        $book->publicationYear = $validation->publicationYear;
        $book->description = $validation->description;
        $book->isbn = $validation->isbn;
        $book->coverImage = $validation->coverImage;

        if (!$book->save()) {
            throw new RuntimeException('Не удалось обновить книгу');
        }

        $this->saveBookAuthors($book, $command->authorIdList);

        $event = new BookUpdated(
            $book->id,
            $book->title,
            $book->publicationYear,
            new \DateTimeImmutable()
        );
        $this->eventDispatcher->dispatch($event);

        return true;
    }

    private function saveBookAuthors(BookModel $book, array $authorIdList): void
    {
        BookAuthorModel::deleteAll(['bookId' => $book->id]);

        foreach ($authorIdList as $authorId) {
            $bookAuthor = new BookAuthorModel();
            $bookAuthor->bookId = $book->id;
            $bookAuthor->authorId = $authorId;
            
            if (!$bookAuthor->save()) {
                throw new RuntimeException(sprintf('Не удалось сохранить связь с автором %d', $authorId));
            }
        }
    }
}
