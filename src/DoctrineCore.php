<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine;

use Doctrine\Common\Annotations\{AnnotationReader, AnnotationRegistry};
use Doctrine\Common\Cache\VoidCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Jarvis\Jarvis;
use Jarvis\Skill\DependencyInjection\ContainerProviderInterface;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class DoctrineCore implements ContainerProviderInterface
{
    public function hydrate(Jarvis $jarvis)
    {
        $jarvis['doctrine.cache'] = function () {
            return new VoidCache();
        };

        $jarvis['doctrine.annotation.driver'] = function () {
            return new AnnotationDriver(new AnnotationReader());
        };

        $jarvis['entyMgr'] = function (Jarvis $jarvis) {
            $settings = $jarvis->settings->get('doctrine');

            $cache = $jarvis['doctrine.cache'];
            $config = Setup::createConfiguration($settings['debug'], $settings['proxies_dir'], $cache);
            $driver = $jarvis['doctrine.annotation.driver'];

            if (isset($settings['entities_paths'])) {
                $driver->addPaths((array) $settings['entities_paths']);
            }

            AnnotationRegistry::registerLoader('class_exists');
            $config->setMetadataDriverImpl($driver);
            $config->setAutoGenerateProxyClasses($settings['debug']);
            $config->setMetadataCacheImpl($cache);
            $config->setResultCacheImpl($cache);
            $config->setQueryCacheImpl($cache);

            $entyMgr = EntityManager::create($settings['dbal'], $config);
            if (
                isset($jarvis['doctrine.orm.entyMgr.decorator'])
                && is_string($fqcn = $jarvis['doctrine.orm.entyMgr.decorator'])
                && is_subclass_of($fqcn, EntityManagerDecorator::class)
            ) {
                $entyMgr = new $fqcn($entyMgr);
            }

            $jarvis->broadcast(DoctrineReadyEvent::READY_EVENT, new DoctrineReadyEvent($entyMgr));

            return $entyMgr;
        };

        $jarvis['db_conn'] = function($jarvis) {
            $jarvis->entyMgr->getConnection();
        };

        $jarvis->lock(['entyMgr', 'db_conn', 'doctrine.annotation.driver']);
    }
}
