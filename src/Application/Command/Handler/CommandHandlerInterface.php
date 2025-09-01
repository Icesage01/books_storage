<?php

namespace src\Application\Command\Handler;

use src\Application\Command\CommandInterface;

interface CommandHandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return mixed
     */
    public function handle(CommandInterface $command): mixed;
}
