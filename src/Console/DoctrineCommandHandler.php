<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine\Console;

use Doctrine\ORM\Tools\SchemaTool;
use Jarvis\Skill\Console\AbstractCommandHandler;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\Command\Command;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class DoctrineCommandHandler extends AbstractCommandHandler
{
    public function handle(Args $args, IO $io, Command $command)
    {
        $io->writeLine("<info>Doctrine ORM entry command. Run `doctrine -h` to see more commands.</info>");

        return 0;
    }

    public function handleDatabaseUpdate(Args $args, IO $io, Command $command)
    {
        $tool = new SchemaTool($this->app->entyMgr);
        $tool->updateSchema($this->app->entyMgr->getMetadataFactory()->getAllMetadata());

        $io->writeLine("<info>Database schema is now up-to-date.</info>");

        return 0;
    }
}
