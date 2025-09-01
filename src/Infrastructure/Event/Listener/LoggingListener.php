<?php

namespace src\Infrastructure\Event\Listener;

use src\Domain\Event\DomainEventInterface;
use src\Domain\Event\EventListenerInterface;

class LoggingListener implements EventListenerInterface
{
    public function handle(DomainEventInterface $event): void
    {
        $logMessage = sprintf(
            '[%s] %s: %s (ID: %s)',
            $event->occurredOn()->format('Y-m-d H:i:s'),
            $event->getEventName(),
            get_class($event),
            $event->getAggregateId()
        );

        error_log($logMessage);
    }

    public function canHandle(DomainEventInterface $event): bool
    {
        return true;
    }
}
