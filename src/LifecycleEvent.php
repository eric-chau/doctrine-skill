<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Jarvis\Skill\EventBroadcaster\SimpleEvent;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class LifecycleEvent extends SimpleEvent
{
    protected $entity;
    protected $eventName;
    protected $previousEvent;

    public function __construct($entity, string $eventName, LifecycleEventArgs $previousEvent)
    {
        $this->entity = $entity;
        $this->eventName = $eventName;
        $this->previousEvent = $previousEvent;
    }

    public function entity()
    {
        return $this->entity;
    }

    public function eventName()
    {
        return $this->eventName;
    }

    public function previousEvent()
    {
        return $this->previousEvent;
    }
}
