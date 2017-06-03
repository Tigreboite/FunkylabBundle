<?php

namespace Tigreboite\FunkylabBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\VarDumper\VarDumper;
use Tigreboite\FunkylabBundle\Event\EntityEvent;
use Tigreboite\FunkylabBundle\TigreboiteFunkylabEvent;

class TestEventListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            TigreboiteFunkylabEvent::ENTITY_CREATED => 'onCreated',
            TigreboiteFunkylabEvent::ENTITY_UPDATED => 'onUpdated',
        ];
    }

    /**
     * @param EntityEvent|null $event
     */
    public function onCreated(EntityEvent $event = null)
    {
        VarDumper::dump('updatcreateded');
        VarDumper::dump($event);
    }

    /**
     * @param EntityEvent|null $event
     */
    public function onUpdated(EntityEvent $event = null)
    {
        VarDumper::dump('updated');
        VarDumper::dump($event);
    }

    /**
     * @param EntityEvent|null $event
     */
    public function onDeleted(EntityEvent $event = null)
    {
        VarDumper::dump('deleted');
        VarDumper::dump($event);
    }

}
