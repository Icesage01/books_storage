<?php

namespace src\Domain\Subscription;

use src\Models\SubscriptionModel as BaseSubscriptionModel;

class SubscriptionModel extends BaseSubscriptionModel
{
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function activate(): void
    {
        $this->status = self::STATUS_ACTIVE;
    }

    public function deactivate(): void
    {
        $this->status = self::STATUS_INACTIVE;
    }

    public function hasEmailNotification(): bool
    {
        return !is_null($this->email) && !empty($this->email);
    }

    public function hasSmsNotification(): bool
    {
        return !is_null($this->phone) && !empty($this->phone);
    }

    public function getNotificationChannels(): array
    {
        $channelList = [];
        
        if ($this->hasEmailNotification()) {
            $channelList[] = 'email';
        }
        
        if ($this->hasSmsNotification()) {
            $channelList[] = 'sms';
        }
        
        return $channelList;
    }
}
