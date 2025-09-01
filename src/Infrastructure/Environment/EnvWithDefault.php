<?php

declare(strict_types=1);

namespace src\Infrastructure\Environment;

class EnvWithDefault
{
    private string $name;
    private mixed $defaultValue;
    
    public function __construct(string $name, mixed $defaultValue)
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue;
    }
    
    public function string(): string
    {
        try {
            return (new Env($this->name))->value();
        } catch (\Exception $e) {
            return $this->defaultValue;
        }
    }
    
    public function bool(): bool
    {
        try {
            return (new BoolEnv($this->name))->value();
        } catch (\Exception $e) {
            return $this->defaultValue;
        }
    }
    
    public function int(): int
    {
        try {
            return (new IntEnv($this->name))->value();
        } catch (\Exception $e) {
            return (int) $this->defaultValue;
        }
    }
    
    public function url(): string
    {
        try {
            return (new UrlEnv($this->name))->value();
        } catch (\Exception $e) {
            return $this->defaultValue;
        }
    }
}
