<?php

namespace src\Domain\Event;

interface EventListenerInterface
{
    /**
     * @param DomainEventInterface $event
     * @return void
     */
    public function handle(DomainEventInterface $event): void;
    
    /**
     * @param DomainEventInterface $event
     * @return bool
     */
    public function canHandle(DomainEventInterface $event): bool;
}
