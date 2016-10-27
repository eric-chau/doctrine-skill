<?php

declare(strict_types = 1);

namespace Jarvis\Skill\Doctrine;

use Doctrine\Common\Annotations\{AnnotationReader, AnnotationRegistry};
use Doctrine\Common\Cache\VoidCache;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Jarvis\Jarvis;
use Jarvis\Skill\DependencyInjection\ContainerProviderInterface;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class DoctrineCore implements ContainerProviderInterface
{
    public function hydrate(Jarvis $app)
    {
        $app['doctrine.cache'] = function () {
            return new VoidCache();
        };

        $app['doctrine.annotation.driver'] = function () {
            return new AnnotationDriver(new AnnotationReader());
        };

        $app['entyMgr'] = function (Jarvis $app): EntityManager {
            $settings = $app['doctrine.settings'];

            $cache = $app['doctrine.cache'];
            $config = Setup::createConfiguration($settings['debug'], $settings['proxies_dir'], $cache);
            $driver = $app['doctrine.annotation.driver'];

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
                isset($app['doctrine.orm.entyMgr.decorator'])
                && is_string($fqcn = $app['doctrine.orm.entyMgr.decorator'])
                && is_subclass_of($fqcn, EntityManagerDecorator::class)
            ) {
                $entyMgr = new $fqcn($entyMgr);
            }

            $entyMgr->getEventManager()->addEventListener([
                Events::preRemove,
                Events::postRemove,
                Events::prePersist,
                Events::postPersist,
                Events::preUpdate,
                Events::postUpdate,
                Events::postLoad,
                Events::preFlush,
                Events::onFlush,
                Events::postFlush,
                Events::onClear,
            ], new EventListener($app));

            $app->broadcast(DoctrineReadyEvent::READY_EVENT, new DoctrineReadyEvent($entyMgr));

            return $entyMgr;
        };

        $app['db_conn'] = function ($app) {
            $app['entyMgr']->getConnection();
        };

        $app->lock(['entyMgr', 'db_conn', 'doctrine.annotation.driver']);
    }
}
