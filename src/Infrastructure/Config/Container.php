<?php

namespace src\Infrastructure\Config;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionParameter;

class Container implements ContainerInterface
{
    private array $definitions = [];
    private array $singletons = [];
    private array $instances = [];

    public function get(string $id): object
    {
        if (!$this->has($id)) {
            throw new InvalidArgumentException(sprintf('Сервис "%s" не зарегистрирован', $id));
        }

        if (isset($this->singletons[$id])) {
            if (!isset($this->instances[$id])) {
                $this->instances[$id] = $this->resolve($this->singletons[$id]);
            }
            return $this->instances[$id];
        }

        return $this->resolve($this->definitions[$id]);
    }

    public function has(string $id): bool
    {
        return isset($this->definitions[$id]) || isset($this->singletons[$id]);
    }

    public function set(string $id, $definition): void
    {
        $this->definitions[$id] = $definition;
    }

    public function setSingleton(string $id, $definition): void
    {
        $this->singletons[$id] = $definition;
    }

    /**
     * @param callable|string $definition
     * @return object
     */
    private function resolve($definition): object
    {
        if (is_callable($definition)) {
            return $definition($this);
        }

        if (is_string($definition)) {
            return $this->createInstance($definition);
        }

        throw new InvalidArgumentException('Неподдерживаемый тип определения');
    }

    /**
     * @param string $className
     * @return object
     */
    private function createInstance(string $className): object
    {
        $reflection = new ReflectionClass($className);
        
        if (!$reflection->isInstantiable()) {
            throw new InvalidArgumentException(sprintf('Класс "%s" не может быть создан', $className));
        }

        $constructor = $reflection->getConstructor();
        
        if ($constructor === null) {
            return new $className();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencies[] = $this->resolveParameter($parameter);
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * @param ReflectionParameter $parameter
     * @return mixed
     */
    private function resolveParameter(ReflectionParameter $parameter): mixed
    {
        $type = $parameter->getType();
        
        if ($type === null || $type->isBuiltin()) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }
            
            throw new InvalidArgumentException(sprintf(
                'Не удается разрешить параметр "%s" для класса "%s"',
                $parameter->getName(),
                $parameter->getDeclaringClass()->getName()
            ));
        }

        $typeName = $type->getName();
        
        if ($this->has($typeName)) {
            return $this->get($typeName);
        }

        return $this->createInstance($typeName);
    }
}
