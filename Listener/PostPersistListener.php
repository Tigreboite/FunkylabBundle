<?php

namespace Tigreboite\FunkylabBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Tigreboite\FunkylabBundle\Event\EntityEvent;
use Tigreboite\FunkylabBundle\TigreboiteFunkylabEvent;

class PostPersistListener
{
    private $dispatcher;

    /**
     * PostPersistListener constructor.
     */
    public function __construct()
    {
        $this->dispatcher = new EventDispatcher();
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $event = new EntityEvent($args->getObject());
        $this->dispatcher->dispatch(TigreboiteFunkylabEvent::ENTITY_UPDATED, $event);
    }


    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $event = new EntityEvent($args);
        $this->dispatcher->dispatch(TigreboiteFunkylabEvent::ENTITY_DELETED, $event);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $event = new EntityEvent($args->getObject());
        $this->dispatcher->dispatch(TigreboiteFunkylabEvent::ENTITY_CREATED, $event);
    }
}
