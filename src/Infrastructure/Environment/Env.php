<?php

declare(strict_types=1);

namespace src\Infrastructure\Environment;

/**
 * Класс для строковых переменных окружения
 */
class Env extends AbstractEnv
{
    public function value(): string
    {
        if (!is_string($this->value)) {
            throw new \Exception(sprintf('Переменная окружения "%s" имеет неправильный тип', $this->name));
        }
        
        return $this->value;
    }
}
