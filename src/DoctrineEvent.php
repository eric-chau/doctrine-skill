<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Jarvis\Skill\EventBroadcaster\SimpleEvent;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class DoctrineEvent extends SimpleEvent
{
    const INIT_EVENT = 'doctrine.init';

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
