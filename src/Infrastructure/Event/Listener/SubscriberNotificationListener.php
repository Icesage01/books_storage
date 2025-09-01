<?php

namespace src\Infrastructure\Event\Listener;

use src\Domain\Event\DomainEventInterface;
use src\Domain\Event\EventListenerInterface;
use src\Domain\Event\Book\BookCreated;
use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Repository\SubscriptionRepositoryInterface;
use src\Infrastructure\Queue\SmsQueueService;

class SubscriberNotificationListener implements EventListenerInterface
{
    public function __construct(
        private readonly SmsQueueService $smsQueueService,
        private readonly BookRepositoryInterface $bookRepository,
        private readonly SubscriptionRepositoryInterface $subscriptionRepository
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        if ($event instanceof BookCreated) {
            $this->handleBookCreated($event);
        }
    }

    public function canHandle(DomainEventInterface $event): bool
    {
        return $event instanceof BookCreated;
    }

    private function handleBookCreated(BookCreated $event): void
    {
        $book = $this->bookRepository->findById($event->getBookId());
        
        if (is_null($book)) {
            return;
        }

        $authorList = $book->authorList;
        
        foreach ($authorList as $bookAuthor) {
            $authorId = $bookAuthor->authorId;
            
            $subscriptionList = $this->subscriptionRepository->findBy(['authorId' => $authorId]);
            
            foreach ($subscriptionList as $subscription) {
                if (!$subscription->isActive() || !$subscription->hasSmsNotification()) {
                    continue;
                }

                $message = sprintf(
                    'Новая книга "%s" от вашего любимого автора! Подробности на сайте.',
                    $event->getTitle()
                );

                $this->smsQueueService->enqueueSms($subscription->phone, $message);
            }
        }
    }
}
