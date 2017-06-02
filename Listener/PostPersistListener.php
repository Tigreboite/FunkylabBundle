<?php

namespace Tigreboite\FunkylabBundle\Listener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Tigreboite\FunkylabBundle\Event\EntityEvent;
use Tigreboite\FunkylabBundle\TigreboiteFunkylabEvent;

class PostPersistListener
{
    private $security;
    private $router;
    private $request;
    private $dispatcher;

    /**
     * PostPersistListener constructor.
     * @param TokenStorage $security
     * @param Router $router
     * @param RequestStack $request
     */
    public function __construct(TokenStorage $security, Router $router, RequestStack $request)
    {
        $this->security = $security;
        $this->request = $request->getCurrentRequest();
        $this->router = $router;
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
        $event = new EntityEvent(null);
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
