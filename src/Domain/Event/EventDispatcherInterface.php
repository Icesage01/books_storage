<?php

namespace src\Domain\Event;

interface EventDispatcherInterface
{
    /**
     * @param DomainEventInterface $event
     * @return void
     */
    public function dispatch(DomainEventInterface $event): void;
    
    /**
     * @param string $eventName
     * @param EventListenerInterface $listener
     * @return void
     */
    public function addListener(string $eventName, EventListenerInterface $listener): void;
    
    /**
     * @param string $eventName
     * @param EventListenerInterface $listener
     * @return void
     */
    public function removeListener(string $eventName, EventListenerInterface $listener): void;
    
    /**
     * @param string $eventName
     * @return EventListenerInterface[]
     */
    public function getListeners(string $eventName): array;
}
