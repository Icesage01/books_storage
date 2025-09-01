<?php

namespace src\Infrastructure\Event\Listener;

use src\Domain\Event\DomainEventInterface;
use src\Domain\Event\EventListenerInterface;
use src\Domain\Event\Book\BookCreated;
use src\Domain\Repository\SubscriptionRepositoryInterface;
use src\Domain\Repository\BookRepositoryInterface;
use src\Infrastructure\Notification\Sms\SmsServiceInterface;
use Yii;

class BookCreatedListener implements EventListenerInterface
{
    public function __construct(
        private readonly SmsServiceInterface $smsService,
        private readonly SubscriptionRepositoryInterface $subscriptionRepository,
        private readonly BookRepositoryInterface $bookRepository
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
            Yii::error(sprintf('Книга с ID %d не найдена для отправки уведомлений', $event->getBookId()));
            return;
        }

        $subscriptionList = $this->subscriptionRepository->findActiveSubscriptionsForBook($book->id);
        
        foreach ($subscriptionList as $subscription) {
            if ($subscription->hasSmsNotification()) {
                $this->sendSmsNotification($subscription, $book);
            }
        }

        Yii::info(sprintf(
            'Отправлено %d SMS уведомлений о новой книге "%s"',
            count($subscriptionList),
            $book->title
        ));
    }

    private function sendSmsNotification($subscription, $book): void
    {
        $authorNames = [];
        foreach ($book->authorList as $bookAuthor) {
            if ($bookAuthor->author) {
                $authorNames[] = $bookAuthor->author->getFullName();
            }
        }

        $message = sprintf(
            'Новая книга "%s" от %s (%d) добавлена в каталог! Подробнее: %s',
            $book->title,
            implode(', ', $authorNames),
            $book->publicationYear,
            Yii::$app->urlManager->createAbsoluteUrl(['/book/view', 'id' => $book->id])
        );

        $this->smsService->send($subscription->phone, $message);
    }
}
