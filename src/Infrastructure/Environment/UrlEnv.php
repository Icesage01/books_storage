<?php

declare(strict_types=1);

namespace src\Infrastructure\Environment;

/**
 * Класс для URL переменных окружения
 */
class UrlEnv extends AbstractEnv
{
    public function value(): string
    {
        if (!is_string($this->value)) {
            throw new \Exception(sprintf('Переменная окружения "%s" имеет неправильный тип', $this->name));
        }
        
        if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
            throw new \Exception(sprintf('Переменная окружения "%s" не является валидным URL', $this->name));
        }
        
        return $this->value;
    }
}
