<?php

namespace Tigreboite\FunkylabBundle\EventListener;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\VarDumper\VarDumper;
use Tigreboite\FunkylabBundle\Event\EntityEvent;
use Tigreboite\FunkylabBundle\TigreboiteFunkylabEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcher;

class TestEventListener implements EventSubscriberInterface
{

    private $security;
    private $router;
    private $request;
    private $dispatcher;

    /**
     * @param TokenStorage $security
     * @param Router $router
     */
    public function __construct(TokenStorage $security, Router $router, RequestStack $request)
    {
        $this->security = $security;
        $this->request = $request->getCurrentRequest();
        $this->router = $router;
    }


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
