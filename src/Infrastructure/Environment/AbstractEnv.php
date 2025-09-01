<?php

declare(strict_types=1);

namespace src\Infrastructure\Environment;

use Exception;

abstract class AbstractEnv
{
    protected mixed $value;
    protected bool $isLoaded = false;
    
    public function __construct(
        protected string $name
    ) {
        $this->loadValue();
    }
    
    protected function loadValue(): void
    {
        if (!$this->isLoaded) {
            EnvironmentLoader::load();
            $this->isLoaded = true;
        }
        
        $this->value = $_ENV[$this->name] ?? getenv($this->name);
        
        if ($this->value === false || is_null($this->value)) {
            throw new Exception(sprintf('Переменная окружения "%s" не определена', $this->name));
        }
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    abstract public function value(): mixed;
}
