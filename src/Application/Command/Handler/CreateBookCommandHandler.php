<?php

namespace src\Application\Command\Handler;

use RuntimeException;
use src\Application\Command\CommandInterface;
use src\Application\Command\CreateBookCommand;
use src\Domain\Book\BookModel;
use src\Domain\Book\BookAuthorModel;
use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Event\EventDispatcherInterface;
use src\Domain\Event\Book\BookCreated;

class CreateBookCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
        private readonly AuthorRepositoryInterface $authorRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    public function handle(CommandInterface $command): int
    {
        if (!$command instanceof CreateBookCommand) {
            throw new \InvalidArgumentException('Неверный тип команды');
        }

        $book = new BookModel();
        $book->title = $command->title;
        $book->publicationYear = $command->publicationYear;
        $book->description = $command->description;
        $book->isbn = $command->isbn;
        $book->coverImage = $command->coverImage;

        if (!$book->save()) {
            throw new \RuntimeException('Не удалось сохранить книгу');
        }

        $this->saveBookAuthors($book, $command->authorIdList);

        $event = new BookCreated(
            $book->id,
            $book->title,
            $book->publicationYear,
            $book->description,
            new \DateTimeImmutable()
        );
        $this->eventDispatcher->dispatch($event);

        return $book->id;
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
