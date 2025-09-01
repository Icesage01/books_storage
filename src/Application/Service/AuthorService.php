<?php

namespace src\Application\Service;

use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use src\Domain\Author\AuthorModel;
use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Event\EventDispatcherInterface;
use src\Domain\Event\Author\AuthorCreated;
use src\Validation\AuthorValidation;

class AuthorService
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @param AuthorValidation $validation
     * @return AuthorModel
     * @throws Exception
     */
    public function createAuthor(AuthorValidation $validation): AuthorModel
    {
        if (!$validation->validate()) {
            throw new InvalidArgumentException('Данные автора не прошли валидацию');
        }

        $author = new AuthorModel();
        $author->load($validation->attributes, '');
        
        if (!$author->save()) {
            throw new Exception('Не удалось сохранить автора');
        }

        $event = new AuthorCreated(
            $author->id,
            $author->firstName,
            $author->lastName,
            $author->middleName,
            $author->biography,
            new DateTimeImmutable()
        );
        
        $this->eventDispatcher->dispatch($event);
        
        return $author;
    }

    /**
     * @param int $id
     * @param AuthorValidation $validation
     * @return AuthorModel
     * @throws Exception
     */
    public function updateAuthor(int $id, AuthorValidation $validation): AuthorModel
    {
        $author = $this->findAuthor($id);
        
        if (!$validation->validate()) {
            throw new InvalidArgumentException('Данные автора не прошли валидацию');
        }

        $author->load($validation->attributes, '');
        
        if (!$author->save()) {
            throw new Exception('Не удалось обновить автора');
        }
        
        return $author;
    }

    /**
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function deleteAuthor(int $id): void
    {
        $author = $this->findAuthor($id);
        
        if (!$author->delete()) {
            throw new Exception('Не удалось удалить автора');
        }
    }

    /**
     * @param int $id
     * @return AuthorModel
     * @throws Exception
     */
    public function findAuthor(int $id): AuthorModel
    {
        $author = $this->authorRepository->findById($id);

        if (is_null($author)) {
            throw new Exception('Автор не найден');
        }

        return $author;
    }

    /**
     * @return AuthorModel[]
     */
    public function getAllAuthors(): array
    {
        return $this->authorRepository->findAll();
    }
}
