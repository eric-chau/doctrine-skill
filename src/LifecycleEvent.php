<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine;

use Jarvis\Skill\EventBroadcaster\SimpleEvent;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class LifecycleEvent extends SimpleEvent
{
    protected $entity;
    protected $eventName;

    public function __construct($entity, string $eventName)
    {
        $this->entity = $entity;
        $this->eventName = $eventName;
    }

    public function entity()
    {
        return $this->entity;
    }

    public function eventName()
    {
        return $this->eventName;
    }
}
