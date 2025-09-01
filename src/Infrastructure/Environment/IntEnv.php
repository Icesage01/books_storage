<?php

declare(strict_types=1);

namespace src\Infrastructure\Environment;

class IntEnv extends AbstractEnv
{
    public function value(): int
    {
        if (!is_string($this->value)) {
            throw new \Exception(sprintf('Переменная окружения "%s" имеет неправильный тип', $this->name));
        }
        
        if (!is_numeric($this->value)) {
            throw new \Exception(sprintf('Переменная окружения "%s" не является числом', $this->name));
        }
        
        return (int) $this->value;
    }
}
