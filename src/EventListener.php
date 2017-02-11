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

    public function preRemove(LifecycleEventArgs $event): void
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function postRemove(LifecycleEventArgs $event): void
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function postPersist(LifecycleEventArgs $event): void
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function postUpdate(LifecycleEventArgs $event): void
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function postLoad(LifecycleEventArgs $event): void
    {
        $this->broadcastEntityEvent($event, __FUNCTION__);
    }

    public function preFlush(EventArgs $event): void
    {
        $this->broadcastEntyMgrEvent($event, __FUNCTION__);
    }

    public function onFlush(EventArgs $event): void
    {
        $this->broadcastEntyMgrEvent($event, __FUNCTION__);
    }

    public function postFlush(EventArgs $event): void
    {
        $this->broadcastEntyMgrEvent($event, __FUNCTION__);
    }

    public function onClear(EventArgs $event): void
    {
        $this->broadcastEntyMgrEvent($event, __FUNCTION__);
    }

    protected function broadcastEntityEvent(LifecycleEventArgs $previousEvent, string $eventType): void
    {
        $entity = $previousEvent->getEntity();

        $event = new LifecycleEvent($entity, strtolower($eventType), $previousEvent);
        foreach (array_merge([get_class($entity)], class_parents($entity)) as $classname) {
            $eventName = str_replace('\\', '.', $classname);
            $eventName = strtolower(sprintf('%s.%s', $eventName, $eventType));

            $this->app->broadcast($eventName, $event);
        }
    }

    protected function broadcastEntyMgrEvent(EventArgs $previousEvent, string $eventType): void
    {
        $entyMgr = $previousEvent->getEntityManager();
        $this->app->broadcast(
            strtolower(sprintf('entitymanager.%s', $eventType)),
            new EntityManagerEvent($entyMgr, $eventType, $previousEvent)
        );

        $uow = $entyMgr->getUnitOfWork();
        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates(),
            $uow->getScheduledEntityDeletions()
        );

        foreach ($entities as $entity) {
            $this->broadcastEntityEvent(
                new LifecycleEventArgs($entity, $entyMgr),
                $eventType
            );
        }
    }
}
