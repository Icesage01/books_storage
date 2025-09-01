<?php

namespace src\Infrastructure\Event\Listener;

use src\Domain\Event\DomainEventInterface;
use src\Domain\Event\EventListenerInterface;
use src\Domain\Event\Book\BookCreated;
use src\Domain\Event\Author\AuthorCreated;
use src\Domain\Event\Subscription\SubscriptionActivated;
use src\Domain\Repository\SubscriptionRepositoryInterface;
use src\Infrastructure\Notification\Sms\SmsServiceInterface;

class SmsNotificationListener implements EventListenerInterface
{
    public function __construct(
        private readonly SmsServiceInterface $smsService,
        private readonly SubscriptionRepositoryInterface $subscriptionRepository
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        if ($event instanceof BookCreated) {
            $this->handleBookCreated($event);
        } elseif ($event instanceof AuthorCreated) {
            $this->handleAuthorCreated($event);
        } elseif ($event instanceof SubscriptionActivated) {
            $this->handleSubscriptionActivated($event);
        }
    }

    public function canHandle(DomainEventInterface $event): bool
    {
        return $event instanceof BookCreated 
            || $event instanceof AuthorCreated 
            || $event instanceof SubscriptionActivated;
    }

    private function handleBookCreated(BookCreated $event): void
    {
        $message = sprintf(
            'Новая книга "%s" (%d) добавлена в каталог!',
            $event->getTitle(),
            $event->getPublicationYear()
        );

        $this->smsService->send('+79001234567', $message);
    }

    private function handleAuthorCreated(AuthorCreated $event): void
    {
        $message = sprintf(
            'Новый автор "%s" добавлен в каталог!',
            $event->getFullName()
        );

        $this->smsService->send('+79001234567', $message);
    }

    private function handleSubscriptionActivated(SubscriptionActivated $event): void
    {
        $message = sprintf(
            'Подписка на автора "%s" активирована. Вы будете получать уведомления о новых книгах.',
            $event->getAuthorName()
        );

        $this->smsService->send($event->getUserPhone(), $message);
    }
}
