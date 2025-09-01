<?php

namespace src\Application\Command\Handler;

use RuntimeException;
use src\Application\Command\CommandInterface;
use src\Application\Command\CommandBusInterface;

class CommandBus implements CommandBusInterface
{
    /**
     * @var array<string, CommandHandlerInterface>
     */
    private array $handlers = [];

    public function register(string $commandClass, CommandHandlerInterface $handler): void
    {
        $this->handlers[$commandClass] = $handler;
    }

    public function dispatch(CommandInterface $command): mixed
    {
        $commandClass = get_class($command);
        
        if (!isset($this->handlers[$commandClass])) {
            throw new RuntimeException(sprintf('Обработчик для команды %s не найден', $commandClass));
        }

        return $this->handlers[$commandClass]->handle($command);
    }
}
