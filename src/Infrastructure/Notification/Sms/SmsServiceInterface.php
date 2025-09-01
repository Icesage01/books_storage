<?php

namespace src\Infrastructure\Notification\Sms;

interface SmsServiceInterface
{
    /**
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function send(string $phone, string $message): bool;
}
