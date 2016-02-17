<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine;

use Doctrine\Common\EventArgs;
use Jarvis\Skill\EventBroadcaster\SimpleEvent;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class EntityManagerEvent extends SimpleEvent
{
    protected $entyMgr;
    protected $eventName;
    protected $previousEvent;

    public function __construct($entyMgr, string $eventName, EventArgs $previousEvent)
    {
        $this->entyMgr = $entyMgr;
        $this->eventName = $eventName;
        $this->previousEvent = $previousEvent;
    }

    public function entyMgr()
    {
        return $this->entyMgr;
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
