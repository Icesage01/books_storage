<?php

namespace src\Application\Command\Handler;

use DateTimeImmutable;
use InvalidArgumentException;
use RuntimeException;
use src\Application\Command\CommandInterface;
use src\Application\Command\CreateAuthorCommand;
use src\Domain\Author\AuthorModel;
use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Event\EventDispatcherInterface;
use src\Domain\Event\Author\AuthorCreated;

class CreateAuthorCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AuthorRepositoryInterface $authorRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    public function handle(CommandInterface $command): int
    {
        if (!$command instanceof CreateAuthorCommand) {
            throw new InvalidArgumentException('Неверный тип команды');
        }

        $validation = $command->getValidation();
        
        $author = new AuthorModel();
        $author->firstName = $validation->firstName;
        $author->lastName = $validation->lastName;
        $author->middleName = $validation->middleName;
        $author->isActive = $validation->isActive ?? true;

        if (!$author->save()) {
            throw new RuntimeException('Не удалось сохранить автора');
        }

        $event = new AuthorCreated(
            $author->id,
            $author->firstName,
            $author->lastName,
            $author->middleName,
            null,
            new DateTimeImmutable()
        );
        $this->eventDispatcher->dispatch($event);

        return $author->id;
    }
}
