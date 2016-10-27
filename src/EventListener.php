<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventArgs;
use Jarvis\Jarvis;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class EventListener
{
    protected $app;

    public function __construct(Jarvis $app)
    {
        $this->app = $app;
    }

    public function preRemove(LifecycleEventArgs $event)
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function postRemove(LifecycleEventArgs $event)
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function postLoad(LifecycleEventArgs $event)
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function preFlush(EventArgs $event)
    {
        $this->broadcastEntyMgrEvent($event, __FUNCTION__);
    }

    public function onFlush(EventArgs $event)
    {
        $this->broadcastEntyMgrEvent($event, __FUNCTION__);
    }

    public function postFlush(EventArgs $event)
    {
        $this->broadcastEntyMgrEvent($event, __FUNCTION__);
    }

    public function onClear(EventArgs $event)
    {
        $this->broadcastEntyMgrEvent($event, __FUNCTION__);
    }

    protected function broadcastEntityEvent(LifecycleEventArgs $previousEvent, string $eventType)
    {
        $entity = $previousEvent->getEntity();

        $event = new LifecycleEvent($entity, strtolower($eventType), $previousEvent);
        foreach (array_merge([get_class($entity)], class_parents($entity)) as $classname) {
            $eventName = str_replace('\\', '.', $classname);
            $eventName = strtolower("{$eventName}.{$eventType}");

            $this->app->broadcast($eventName, $event);
        }
    }

    protected function broadcastEntyMgrEvent(EventArgs $previousEvent, $eventType)
    {
        $this->app->broadcast(
            strtolower("entitymanager.{$eventType}"),
            new EntityManagerEvent($previousEvent->getEntityManager(), $eventType, $previousEvent)
        );
    }
}
