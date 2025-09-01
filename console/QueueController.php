<?php

namespace console;

use src\Infrastructure\Config\ContainerManager;
use src\Infrastructure\Queue\SmsQueueService;
use yii\console\Controller;

class QueueController extends Controller
{
    public function actionProcessSms(): int
    {
        try {
            $smsQueueService = ContainerManager::get(SmsQueueService::class);
            
            $processedCount = $smsQueueService->processQueue();
            
            if ($processedCount > 0) {
                $this->stdout(sprintf("Обработано %d SMS сообщений\n", $processedCount));
            } else {
                $this->stdout("Очередь SMS пуста\n");
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->stderr(sprintf("Ошибка обработки очереди SMS: %s\n", $e->getMessage()));
            return 1;
        }
    }

    public function actionStatus(): int
    {
        try {
            $smsQueueService = ContainerManager::get(SmsQueueService::class);
            
            $queueSize = $smsQueueService->getQueueSize();
            
            $this->stdout(sprintf("Размер очереди SMS: %d сообщений\n", $queueSize));
            
            return 0;
        } catch (\Exception $e) {
            $this->stderr(sprintf("Ошибка получения статуса очереди: %s\n", $e->getMessage()));
            return 1;
        }
    }

    public function actionTestSms(string $phone, string $message = 'Тестовое SMS сообщение'): int
    {
        try {
            $smsQueueService = ContainerManager::get(SmsQueueService::class);
            
            $success = $smsQueueService->enqueueSms($phone, $message);
            
            if ($success) {
                $this->stdout(sprintf("SMS добавлено в очередь для номера %s\n", $phone));
                return 0;
            } else {
                $this->stderr("Ошибка добавления SMS в очередь\n");
                return 1;
            }
        } catch (\Exception $e) {
            $this->stderr(sprintf("Ошибка тестирования SMS: %s\n", $e->getMessage()));
            return 1;
        }
    }
}
