<?php

namespace Tigreboite\FunkylabBundle\Listener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Tigreboite\FunkylabBundle\Entity\Activity;
use Symfony\Component\HttpFoundation\RequestStack;

class PostPersistListener
{
    private $security;
    private $router;
    private $request;

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

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
//        $this->createActivity($args, Activity::ACTION_UPDATE);
    }


    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
//        $this->createActivity($args, Activity::ACTION_DELETE);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
//        $this->createActivity($args, Activity::ACTION_CREATED);
    }
}
