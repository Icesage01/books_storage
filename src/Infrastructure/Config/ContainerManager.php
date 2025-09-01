<?php

namespace src\Infrastructure\Config;

use src\Infrastructure\Config\Container;
use src\Infrastructure\Config\ContainerInterface;
use src\Infrastructure\Event\EventDispatcher;
use src\Infrastructure\Event\Listener\BookCreatedListener;
use src\Infrastructure\Event\Listener\LoggingListener;
use src\Infrastructure\Event\Listener\SmsNotificationListener;
use src\Infrastructure\Event\Listener\SubscriberNotificationListener;
use src\Infrastructure\External\SmsPilot\SmsPilotService;
use src\Infrastructure\Queue\QueueInterface;
use src\Infrastructure\Queue\RedisQueue;
use src\Infrastructure\Queue\SmsQueueService;
use src\Infrastructure\Notification\Sms\SmsServiceInterface;
use src\Domain\Event\EventDispatcherInterface;
use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Repository\SubscriptionRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;
use src\Infrastructure\Database\Repository\AuthorRepository;
use src\Infrastructure\Database\Repository\BookRepository;
use src\Infrastructure\Database\Repository\SubscriptionRepository;
use src\Infrastructure\Database\Repository\UserRepository;
use src\Application\Command\Handler\CommandBus;
use src\Application\Command\CommandBusInterface;
use src\Application\Query\Handler\QueryBus;
use src\Application\Query\QueryBusInterface;
use src\Application\Command\Handler\CreateAuthorCommandHandler;
use src\Application\Command\Handler\CreateBookCommandHandler;
use src\Application\Command\Handler\DeleteAuthorCommandHandler;
use src\Application\Command\Handler\DeleteBookCommandHandler;
use src\Application\Command\Handler\UpdateAuthorCommandHandler;
use src\Application\Command\Handler\UpdateBookCommandHandler;
use src\Application\Query\Handler\GetAuthorQueryHandler;
use src\Application\Query\Handler\GetTopAuthorsQueryHandler;
use src\Application\Command\CreateAuthorCommand;
use src\Application\Command\CreateBookCommand;
use src\Application\Command\DeleteAuthorCommand;
use src\Application\Command\DeleteBookCommand;
use src\Application\Command\UpdateAuthorCommand;
use src\Application\Command\UpdateBookCommand;
use src\Application\Query\GetAuthorQuery;
use src\Application\Query\GetTopAuthorsQuery;
use src\Application\Service\PaginationService;

class ContainerManager
{
    private static ?ContainerInterface $container = null;

    public static function getContainer(): ContainerInterface
    {
        if (self::$container === null) {
            self::$container = new Container();
            self::registerServices(self::$container);
        }

        return self::$container;
    }

    public static function get(string $id)
    {
        return self::getContainer()->get($id);
    }

    public static function has(string $id): bool
    {
        return self::getContainer()->has($id);
    }

    private static function registerServices(ContainerInterface $container): void
    {
        // Репозитории
        $container->set(AuthorRepositoryInterface::class, AuthorRepository::class);
        $container->set(BookRepositoryInterface::class, BookRepository::class);
        $container->set(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $container->set(UserRepositoryInterface::class, UserRepository::class);

        // SMS сервисы
        $container->set(SmsServiceInterface::class, SmsPilotService::class);
        $container->set(QueueInterface::class, RedisQueue::class);
        $container->set(SmsQueueService::class, SmsQueueService::class);

        // Event Dispatcher
        $container->set(EventDispatcherInterface::class, function (ContainerInterface $container) {
            $dispatcher = new EventDispatcher();
            
            $dispatcher->addListener('book.created', $container->get(SubscriberNotificationListener::class));
            $dispatcher->addListener('book.created', $container->get(LoggingListener::class));
            $dispatcher->addListener('author.created', $container->get(LoggingListener::class));
            
            return $dispatcher;
        });

        // Command Bus
        $container->set(CommandBusInterface::class, function (ContainerInterface $container) {
            $commandBus = new CommandBus();
            
            $commandBus->register(CreateAuthorCommand::class, $container->get(CreateAuthorCommandHandler::class));
            $commandBus->register(CreateBookCommand::class, $container->get(CreateBookCommandHandler::class));
            $commandBus->register(DeleteAuthorCommand::class, $container->get(DeleteAuthorCommandHandler::class));
            $commandBus->register(DeleteBookCommand::class, $container->get(DeleteBookCommandHandler::class));
            $commandBus->register(UpdateAuthorCommand::class, $container->get(UpdateAuthorCommandHandler::class));
            $commandBus->register(UpdateBookCommand::class, $container->get(UpdateBookCommandHandler::class));
            
            return $commandBus;
        });
        $container->set(CommandBus::class, function (ContainerInterface $container) {
            return $container->get(CommandBusInterface::class);
        });

        // Query Bus
        $container->set(QueryBusInterface::class, function (ContainerInterface $container) {
            $queryBus = new QueryBus();
            
            $queryBus->register(GetAuthorQuery::class, $container->get(GetAuthorQueryHandler::class));
            $queryBus->register(GetTopAuthorsQuery::class, $container->get(GetTopAuthorsQueryHandler::class));
            
            return $queryBus;
        });
        $container->set(QueryBus::class, function (ContainerInterface $container) {
            return $container->get(QueryBusInterface::class);
        });

        // Command Handlers
        $container->set(CreateAuthorCommandHandler::class, CreateAuthorCommandHandler::class);
        $container->set(CreateBookCommandHandler::class, CreateBookCommandHandler::class);
        $container->set(DeleteAuthorCommandHandler::class, DeleteAuthorCommandHandler::class);
        $container->set(DeleteBookCommandHandler::class, DeleteBookCommandHandler::class);
        $container->set(UpdateAuthorCommandHandler::class, UpdateAuthorCommandHandler::class);
        $container->set(UpdateBookCommandHandler::class, UpdateBookCommandHandler::class);

        // Query Handlers
        $container->set(GetAuthorQueryHandler::class, GetAuthorQueryHandler::class);
        $container->set(GetTopAuthorsQueryHandler::class, GetTopAuthorsQueryHandler::class);

        // Event Listeners
        $container->set(BookCreatedListener::class, BookCreatedListener::class);
        $container->set(LoggingListener::class, LoggingListener::class);
        $container->set(SmsNotificationListener::class, SmsNotificationListener::class);
        $container->set(SubscriberNotificationListener::class, SubscriberNotificationListener::class);

        // Pagination Services
        $container->set('BookPaginationService', function (ContainerInterface $container) {
            return new PaginationService($container->get(BookRepositoryInterface::class), 12);
        });
        $container->set('AuthorPaginationService', function (ContainerInterface $container) {
            return new PaginationService($container->get(AuthorRepositoryInterface::class), 15);
        });
    }
}
