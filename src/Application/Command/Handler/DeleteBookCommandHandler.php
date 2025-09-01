<?php

namespace src\Application\Command\Handler;

use DateTimeImmutable;
use InvalidArgumentException;
use RuntimeException;
use src\Application\Command\CommandInterface;
use src\Application\Command\DeleteBookCommand;
use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Event\EventDispatcherInterface;
use src\Domain\Event\Book\BookDeleted;

class DeleteBookCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    public function handle(CommandInterface $command): bool
    {
        if (!$command instanceof DeleteBookCommand) {
            throw new InvalidArgumentException('Неверный тип команды');
        }

        $book = $this->bookRepository->findById($command->bookId);
        
        if (is_null($book)) {
            throw new RuntimeException(sprintf('Книга с ID %d не найдена', $command->bookId));
        }

        $bookTitle = $book->title;
        $bookYear = $book->publicationYear;

        if (!$book->delete()) {
            throw new RuntimeException('Не удалось удалить книгу');
        }

            $event = new BookDeleted(
            $command->bookId,
            $bookTitle,
            $bookYear,
            new DateTimeImmutable()
        );
        $this->eventDispatcher->dispatch($event);

        return true;
    }
}
