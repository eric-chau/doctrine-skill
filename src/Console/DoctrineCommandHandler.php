<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine\Console;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Platforms\MySqlPlatform;
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

    public function handleDatabaseCreate(Args $args, IO $io, Command $command)
    {
        $params = $this->app->settings->get('doctrine')['dbal'];
        $name = $params['dbname'] ?? null;
        if (false == $name) {
            $io->writeLine("<error>Database's name is missing from provided settings.</error>");

            return 1;
        }

        unset($params['dbname']);

        $tmpConn = DriverManager::getConnection($params);
        if (!($tmpConn->getDriver()->getDatabasePlatform() instanceof MySqlPlatform)) {
            $io->writeLine("<warn>This command only support MySQL database, sorry!</warn>");

            return 1;
        }

        $shouldNotCreateDatabase = in_array($name, $tmpConn->getSchemaManager()->listDatabases());

        if ($shouldNotCreateDatabase) {
            $io->writeLine("<warn>Database '{$name}' already exists.</warn>");

            return 1;
        }

        $sql = "CREATE DATABASE `{$name}`";
        if (isset($params['collation']) && isset($params['charset'])) {
            $sql .= ' CHARACTER SET ' . $params['charset'] . ' COLLATE ' . $params['collation'];
        }

        $tmpConn->executeUpdate($sql);
        $tmpConn->close();

        $io->writeLine("<info>Database '{$name}' created.</info>");

        return 0;
    }

    public function handleDatabaseUpdate(Args $args, IO $io, Command $command)
    {
        $tool = new SchemaTool($this->app->entyMgr);
        $tool->updateSchema($this->app->entyMgr->getMetadataFactory()->getAllMetadata());

        $io->writeLine("<info>Database schema updated.</info>");

        return 0;
    }
}
