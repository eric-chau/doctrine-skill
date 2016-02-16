<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Jarvis\Jarvis;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class EventListener
{
    protected $jarvis;

    public function __construct(Jarvis $jarvis)
    {
        $this->jarvis = $jarvis;
    }

    public function preRemove(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    public function postRemove(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    public function postLoad(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    public function preFlush(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    public function onFlush(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    public function postFlush(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    public function onClear(LifecycleEventArgs $event)
    {
        $this->broadcastEvent($event->getEntity(), __FUNCTION__);
    }

    protected function broadcastEvent($entity, string $eventType)
    {
        $event = new LifecycleEvent($entity, strtolower($eventType));
        foreach (array_merge([get_class($entity)], class_parents($entity)) as $classname) {
            $eventName = str_replace('\\', '.', $classname);
            $eventName = strtolower("$eventName.$eventType");

            $this->jarvis->broadcast($eventName, $event);
        }
    }
}
