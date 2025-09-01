<?php

namespace src\Infrastructure\Database\Repository;

use src\Domain\Repository\SubscriptionRepositoryInterface;
use src\Domain\Subscription\SubscriptionModel;

class SubscriptionRepository extends AbstractRepository implements SubscriptionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(SubscriptionModel::class);
    }
    
    public function findByUser(int $userId): array
    {
        return $this->findBy(['userId' => $userId]);
    }
    
    public function findActiveSubscriptions(): array
    {
        $now = date('Y-m-d H:i:s');
        return $this->getQuery()
            ->where(['and',
                ['status' => 'active'],
                ['>', 'expiresAt', $now]
            ])
            ->all();
    }
    
    public function findByStatus(string $status): array
    {
        return $this->findBy(['status' => $status]);
    }
    
    public function findExpiringSoon(int $days = 7): array
    {
        $now = date('Y-m-d H:i:s');
        $expiresDate = date('Y-m-d H:i:s', strtotime(sprintf('+%d days', $days)));
        
        return $this->getQuery()
            ->where(['and',
                ['status' => 'active'],
                ['>', 'expiresAt', $now],
                ['<=', 'expiresAt', $expiresDate]
            ])
            ->all();
    }
    
    public function findExpiredSubscriptions(): array
    {
        $now = date('Y-m-d H:i:s');
        return $this->getQuery()
            ->where(['and',
                ['status' => 'active'],
                ['<=', 'expiresAt', $now]
            ])
            ->all();
    }
    
    /**
     * @return SubscriptionModel[]
     */
    public function findActiveSubscriptionsForBook(int $bookId): array
    {
        $now = date('Y-m-d H:i:s');
        return $this->getQuery()
            ->where(['and',
                ['bookId' => $bookId],
                ['status' => 'active'],
                ['>', 'expiresAt', $now]
            ])
            ->all();
    }
    
    /**
     * @return SubscriptionModel|null
     */
    public function findByPhone(string $phone): ?SubscriptionModel
    {
        return $this->findOneBy(['phone' => $phone]);
    }
    
}
