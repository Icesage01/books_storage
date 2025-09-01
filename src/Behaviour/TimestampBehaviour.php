<?php

namespace src\Behaviour;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class TimestampBehaviour extends Behavior
{
    public string $createdAtAttribute = 'createdAt';
    public string $updatedAtAttribute = 'updatedAt';
    public string $dateFormat = 'Y-m-d H:i:s';

    public function events(): array
    {
        $events = [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
        ];
        
        if ($this->updatedAtAttribute && $this->hasAttribute($this->updatedAtAttribute)) {
            $events[ActiveRecord::EVENT_BEFORE_UPDATE] = 'beforeUpdate';
        }
        
        return $events;
    }

    public function beforeInsert(): void
    {
        if ($this->hasAttribute($this->createdAtAttribute)) {
            $this->owner->{$this->createdAtAttribute} = date($this->dateFormat);
        }
        
        if ($this->updatedAtAttribute && $this->hasAttribute($this->updatedAtAttribute)) {
            $this->owner->{$this->updatedAtAttribute} = date($this->dateFormat);
        }
    }

    public function beforeUpdate(): void
    {
        if ($this->updatedAtAttribute && $this->hasAttribute($this->updatedAtAttribute)) {
            $this->owner->{$this->updatedAtAttribute} = date($this->dateFormat);
        }
    }

    private function hasAttribute(string $attribute): bool
    {
        return $this->owner->hasAttribute($attribute);
    }
}
