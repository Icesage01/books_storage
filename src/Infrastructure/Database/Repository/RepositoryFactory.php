<?php

namespace src\Infrastructure\Database\Repository;

use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Repository\SubscriptionRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;

class RepositoryFactory
{
    private UserRepository $userRepository;
    private BookRepository $bookRepository;
    private AuthorRepository $authorRepository;
    private SubscriptionRepository $subscriptionRepository;
    
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->bookRepository = new BookRepository();
        $this->authorRepository = new AuthorRepository();
        $this->subscriptionRepository = new SubscriptionRepository();
    }
    
    public function getUserRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }
    
    public function getBookRepository(): BookRepositoryInterface
    {
        return $this->bookRepository;
    }
    
    public function getAuthorRepository(): AuthorRepositoryInterface
    {
        return $this->authorRepository;
    }
    
    public function getSubscriptionRepository(): SubscriptionRepositoryInterface
    {
        return $this->subscriptionRepository;
    }
}
