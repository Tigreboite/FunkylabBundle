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
    private $user;

    /**
     * @param TokenStorage $security
     * @param Router       $router
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
        $this->createActivity($args, Activity::ACTION_UPDATE);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->createActivity($args, Activity::ACTION_DELETE);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->createActivity($args, Activity::ACTION_CREATED);
    }

    /**
     * @param LifecycleEventArgs $args
     * @param                    $action
     */
    private function createActivity(LifecycleEventArgs $args, $action)
    {
        // $route = $this->router->match($this->request->getPathInfo());
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $this->user = $this->getLoggedUser();
        if ($this->request) {
            $path = $this->request->getPathInfo();
            if ($this->str_starts_with($path, '/admin')) {
                if (!($entity instanceof Activity) &&  $this->user) {
                    /*$activity = new Activity();
                    $activity->setCreatedBy($this->user->getFirstname()." ".$this->user->getLastname());
                    $activity->setAction($action);
                    $activity->setEntityId($entity->getId());
                    $activity->setEntityType(get_class($entity));
                    $entityManager->persist($activity);
                    $entityManager->flush();*/
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function getLoggedUser()
    {
        $token = $this->security->getToken();
        if ($token) {
            $user = $token->getUser();

            return $user && $user != 'anon.' ? $user : false;
        } else {
            return false;
        }
    }

    public function str_starts_with($haystack, $needle)
    {
        return strpos($haystack, $needle) === 0;
    }
}
