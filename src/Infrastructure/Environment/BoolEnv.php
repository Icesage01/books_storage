<?php

declare(strict_types=1);

namespace src\Infrastructure\Environment;

class BoolEnv extends AbstractEnv
{
    public function value(): bool
    {
        if (!is_string($this->value)) {
            throw new \Exception(sprintf('Переменная окружения "%s" имеет неправильный тип', $this->name));
        }
        
        return in_array(mb_strtolower($this->value), ['1', 'true', 'yes', 'on']);
    }
}
