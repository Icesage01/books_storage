<?php

namespace src\Infrastructure\Queue;

use src\Infrastructure\Notification\Sms\SmsServiceInterface;
use Yii;

class SmsQueueService
{
    private const MAX_ATTEMPTS = 3;

    public function __construct(
        private readonly QueueInterface $queue,
        private readonly SmsServiceInterface $smsService
    ) {}

    public function enqueueSms(string $phone, string $message): bool
    {
        $job = new SmsQueueJob($phone, $message);
        return $this->queue->push(SmsQueueJob::QUEUE_NAME, $job->toArray());
    }

    public function processQueue(): int
    {
        $processedCount = 0;
        $maxJobs = 100; // Ограничиваем количество обрабатываемых задач за раз

        while ($processedCount < $maxJobs) {
            $jobData = $this->queue->pop(SmsQueueJob::QUEUE_NAME);
            
            if (is_null($jobData)) {
                break;
            }

            $job = SmsQueueJob::fromArray($jobData);
            
            if ($this->processJob($job)) {
                $processedCount++;
            }
        }

        return $processedCount;
    }

    private function processJob(SmsQueueJob $job): bool
    {
        try {
            $success = $this->smsService->send($job->getPhone(), $job->getMessage());
            
            if ($success) {
                Yii::info(sprintf('SMS успешно отправлено на %s', $job->getPhone()));
                return true;
            }

            if ($job->getAttempts() < self::MAX_ATTEMPTS) {
                $retryJob = $job->incrementAttempts();
                $this->queue->push(SmsQueueJob::QUEUE_NAME, $retryJob->toArray());
                Yii::warning(sprintf(
                    'SMS отправка не удалась для %s, попытка %d из %d',
                    $job->getPhone(),
                    $job->getAttempts() + 1,
                    self::MAX_ATTEMPTS
                ));
            } else {
                Yii::error(sprintf(
                    'SMS отправка не удалась для %s после %d попыток',
                    $job->getPhone(),
                    self::MAX_ATTEMPTS
                ));
            }

            return false;
        } catch (\Exception $e) {
            Yii::error(sprintf(
                'Ошибка при отправке SMS на %s: %s',
                $job->getPhone(),
                $e->getMessage()
            ));

            if ($job->getAttempts() < self::MAX_ATTEMPTS) {
                $retryJob = $job->incrementAttempts();
                $this->queue->push(SmsQueueJob::QUEUE_NAME, $retryJob->toArray());
            }

            return false;
        }
    }

    public function getQueueSize(): int
    {
        return $this->queue->size(SmsQueueJob::QUEUE_NAME);
    }
}
