<?php

namespace src\Domain\Repository;

use src\Domain\User\UserModel;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * @return UserModel
     */
    public function findByUsername(string $username): ?UserModel;

    /**
     * @return UserModel
     */
    public function findByEmail(string $email): ?UserModel;

    /**
     * @return UserModel
     */
    public function findByAccessToken(string $token): ?UserModel;

    /**
     * @return UserModel[]
     */
    public function findActiveUsers(): array;

    /**
     * @return UserModel[]
     */
    public function findByStatus(int $status): array;
}
