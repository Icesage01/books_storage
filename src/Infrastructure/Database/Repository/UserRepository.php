<?php

namespace src\Infrastructure\Database\Repository;

use src\Domain\Repository\UserRepositoryInterface;
use src\Domain\User\UserModel;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(UserModel::class);
    }
    
    public function findByUsername(string $username): ?UserModel
    {
        return $this->findOneBy(['username' => $username]);
    }
    
    public function findByEmail(string $email): ?UserModel
    {
        return $this->findOneBy(['email' => $email]);
    }
    
    public function findByAccessToken(string $token): ?UserModel
    {
        return $this->findOneBy(['accessToken' => $token]);
    }
    
    public function findActiveUsers(): array
    {
        return $this->findBy(['status' => UserModel::STATUS_ACTIVE]);
    }
    
    public function findByStatus(int $status): array
    {
        return $this->findBy(['status' => $status]);
    }
}
