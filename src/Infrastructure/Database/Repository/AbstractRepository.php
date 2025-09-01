<?php

namespace src\Infrastructure\Database\Repository;

use src\Domain\Repository\RepositoryInterface;
use yii\db\ActiveRecord;

abstract class AbstractRepository implements RepositoryInterface
{
    /** @var class-string<T> */
    protected string $modelClass;
    
    /**
     * @param class-string<T> $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }
    
    /**
     * @return T|null
     */
    public function findById(int $id): ?object
    {
        return $this->modelClass::findOne($id);
    }
    
    /**
     * @return T[]
     */
    public function findAll(): array
    {
        return $this->modelClass::find()->all();
    }
    
    public function save(object $entity): bool
    {
        if (!$entity instanceof ActiveRecord) {
            return false;
        }
        
        return $entity->save();
    }
    
    public function delete(object $entity): bool
    {
        if (!$entity instanceof ActiveRecord) {
            return false;
        }
        
        return $entity->delete() !== false;
    }
    
    /**
     * @return T[]
     */
    public function findBy(array $criteria): array
    {
        return $this->modelClass::find()->where($criteria)->all();
    }
    
    /**
     * @return T|null
     */
    public function findOneBy(array $criteria): ?object
    {
        return $this->modelClass::findOne($criteria);
    }

    /**
     * @param array $criteria Критерии фильтрации
     * @param array $orderBy Сортировка ['field' => SORT_ASC/SORT_DESC]
     * @param int|null $limit Лимит записей
     * @param int|null $offset Смещение для пагинации
     * @return array
     */
    public function findWithOptions(
        array $criteria = [],
        array $orderBy = [],
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $query = $this->getQuery();
        
        if (!empty($criteria)) {
            $query->where($criteria);
        }
        
        if (!empty($orderBy)) {
            $query->orderBy($orderBy);
        }
        
        if (!is_null($limit)) {
            $query->limit($limit);
        }
        
        if (!is_null($offset)) {
            $query->offset($offset);
        }
        
        return $query->all();
    }

    /**
     * @param array $criteria Критерии фильтрации
     * @return int
     */
    public function count(array $criteria = []): int
    {
        $query = $this->getQuery();
        
        if (!empty($criteria)) {
            $query->where($criteria);
        }
        
        return (int) $query->count();
    }

    /**
     * @return T
     */
    protected function createModel(): object
    {
        return new $this->modelClass();
    }

    protected function getQuery()
    {
        return $this->modelClass::find();
    }
}
