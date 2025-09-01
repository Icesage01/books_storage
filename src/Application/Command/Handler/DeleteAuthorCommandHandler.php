<?php

namespace src\Application\Command\Handler;

use InvalidArgumentException;
use src\Application\Command\CommandInterface;
use src\Application\Command\DeleteAuthorCommand;
use src\Domain\Repository\AuthorRepositoryInterface;

class DeleteAuthorCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AuthorRepositoryInterface $authorRepository
    ) {}

    public function handle(CommandInterface $command): mixed
    {
        if (!$command instanceof DeleteAuthorCommand) {
            throw new InvalidArgumentException('Неверный тип команды');
        }

        $author = $this->authorRepository->findById($command->getAuthorId());
        
        if (is_null($author)) {
            throw new InvalidArgumentException('Автор не найден');
        }

        $this->authorRepository->delete($author);
        
        return null;
    }
}
