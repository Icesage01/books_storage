<?php

namespace src\Domain\Event;

use DateTimeImmutable;

interface DomainEventInterface
{
    /**
     * @return DateTimeImmutable
     */
    public function occurredOn(): DateTimeImmutable;
    
    /**
     * @return string
     */
    public function getEventName(): string;
    
    /**
     * @return string
     */
    public function getAggregateId(): string;
}
