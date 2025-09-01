<?php

namespace src\Infrastructure\Event;

use src\Domain\Event\DomainEventInterface;
use src\Domain\Event\EventListenerInterface;
use src\Domain\Event\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array<string, EventListenerInterface[]>
     */
    private array $listeners = [];

    public function dispatch(DomainEventInterface $event): void
    {
        $eventName = $event->getEventName();
        
        if (!isset($this->listeners[$eventName])) {
            return;
        }

        foreach ($this->listeners[$eventName] as $listener) {
            if ($listener->canHandle($event)) {
                try {
                    $listener->handle($event);
                } catch (\Exception $e) {
                    error_log(sprintf(
                        'Ошибка при обработке события %s: %s',
                        $eventName,
                        $e->getMessage()
                    ));
                }
            }
        }
    }

    public function addListener(string $eventName, EventListenerInterface $listener): void
    {
        if (!isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = [];
        }

        $this->listeners[$eventName][] = $listener;
    }

    public function removeListener(string $eventName, EventListenerInterface $listener): void
    {
        if (!isset($this->listeners[$eventName])) {
            return;
        }

        $key = array_search($listener, $this->listeners[$eventName], true);
        
        if ($key !== false) {
            unset($this->listeners[$eventName][$key]);
            $this->listeners[$eventName] = array_values($this->listeners[$eventName]);
        }
    }

    public function getListeners(string $eventName): array
    {
        return $this->listeners[$eventName] ?? [];
    }
}
