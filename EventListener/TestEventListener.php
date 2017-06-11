<?php

namespace Tigreboite\FunkylabBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tigreboite\FunkylabBundle\Event\EntityEvent;
use Tigreboite\FunkylabBundle\TigreboiteFunkylabEvent;

class TestEventListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            TigreboiteFunkylabEvent::ENTITY_CREATED => 'onCreated',
            TigreboiteFunkylabEvent::ENTITY_UPDATED => 'onUpdated',
            TigreboiteFunkylabEvent::ENTITY_DELETED => 'onDeleted',
        ];
    }

    /**
     * @param EntityEvent|null $event
     */
    public function onCreated(EntityEvent $event = null)
    {
    }

    /**
     * @param EntityEvent|null $event
     */
    public function onUpdated(EntityEvent $event = null)
    {
    }

    /**
     * @param EntityEvent|null $event
     */
    public function onDeleted(EntityEvent $event = null)
    {
    }
}
