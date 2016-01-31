<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Jarvis\Skill\EventBroadcaster\PermanentEvent;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class DoctrineReadyEvent extends PermanentEvent
{
    const READY_EVENT = 'doctrine.ready';

    private $enty;

    public function __construct(EntityManagerInterface $entyMgr)
    {
        $this->entyMgr = $entyMgr;
    }

    public function entyMgr()
    {
        return $this->entyMgr;
    }
}
