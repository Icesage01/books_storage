<?php

namespace src\Application\Command;

interface CommandBusInterface
{
    /**
     * @param CommandInterface $command
     * @return mixed
     */
    public function dispatch(CommandInterface $command): mixed;
}
