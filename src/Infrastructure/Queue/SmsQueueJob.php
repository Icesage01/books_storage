<?php

namespace src\Infrastructure\Queue;

class SmsQueueJob
{
    public const QUEUE_NAME = 'sms_notifications';

    public function __construct(
        private readonly string $phone,
        private readonly string $message,
        private readonly int $attempts = 0
    ) {}

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getAttempts(): int
    {
        return $this->attempts;
    }

    public function incrementAttempts(): self
    {
        return new self($this->phone, $this->message, $this->attempts + 1);
    }

    public function toArray(): array
    {
        return [
            'phone' => $this->phone,
            'message' => $this->message,
            'attempts' => $this->attempts,
            'created_at' => time(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['phone'],
            $data['message'],
            $data['attempts'] ?? 0
        );
    }
}
