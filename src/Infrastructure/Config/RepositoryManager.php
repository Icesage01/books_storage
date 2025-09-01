<?php

namespace src\Infrastructure\Config;

use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Repository\SubscriptionRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;
use src\Infrastructure\Database\Repository\AuthorRepository;
use src\Infrastructure\Database\Repository\BookRepository;
use src\Infrastructure\Database\Repository\SubscriptionRepository;
use src\Infrastructure\Database\Repository\UserRepository;

class RepositoryManager
{
    private static ?AuthorRepositoryInterface $authorRepository = null;
    private static ?BookRepositoryInterface $bookRepository = null;
    private static ?SubscriptionRepositoryInterface $subscriptionRepository = null;
    private static ?UserRepositoryInterface $userRepository = null;

    public static function getAuthorRepository(): AuthorRepositoryInterface
    {
        if (is_null(self::$authorRepository)) {
            self::$authorRepository = new AuthorRepository();
        }
        return self::$authorRepository;
    }

    public static function getBookRepository(): BookRepositoryInterface
    {
        if (is_null(self::$bookRepository)) {
            self::$bookRepository = new BookRepository();
        }
        return self::$bookRepository;
    }

    public static function getSubscriptionRepository(): SubscriptionRepositoryInterface
    {
        if (is_null(self::$subscriptionRepository)) {
            self::$subscriptionRepository = new SubscriptionRepository();
        }
        return self::$subscriptionRepository;
    }

    public static function getUserRepository(): UserRepositoryInterface
    {
        if (is_null(self::$userRepository)) {
            self::$userRepository = new UserRepository();
        }
        return self::$userRepository;
    }

    public static function clear(): void
    {
        self::$authorRepository = null;
        self::$bookRepository = null;
        self::$subscriptionRepository = null;
        self::$userRepository = null;
    }
}