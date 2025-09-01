<?php

namespace src\Application\Command\Handler;

use InvalidArgumentException;
use src\Application\Command\CommandInterface;
use src\Application\Command\UpdateAuthorCommand;
use src\Domain\Repository\AuthorRepositoryInterface;

class UpdateAuthorCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AuthorRepositoryInterface $authorRepository
    ) {}

    public function handle(CommandInterface $command): mixed
    {
        if (!$command instanceof UpdateAuthorCommand) {
            throw new InvalidArgumentException('Неверный тип команды');
        }

        $author = $this->authorRepository->findById($command->getAuthorId());
        
        if (is_null($author)) {
            throw new InvalidArgumentException('Автор не найден');
        }

        $validation = $command->getValidation();
        
        $author->firstName = $validation->firstName;
        $author->lastName = $validation->lastName;
        $author->middleName = $validation->middleName;
        $author->isActive = $validation->isActive ?? true;
        
        $this->authorRepository->save($author);
        
        return null;
    }
}
