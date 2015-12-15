<?php
namespace Tigreboite\FunkylabBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Tigreboite\FunkylabBundle\Entity\Activity;

class PostPersistListener
{
    private $security;
    private $router;
    private $user;

    public function __construct(TokenStorage $security, Router $router) {

        $this->security  = $security;
        $this->router    = $router;

    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->createActivity($args,Activity::ACTION_UPDATE);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->createActivity($args,Activity::ACTION_DELETE);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->createActivity($args,Activity::ACTION_CREATED);
    }

    private function createActivity(LifecycleEventArgs $args,$action)
    {
        $entity         = $args->getEntity();
        $entityManager  = $args->getEntityManager();
        $this->user     = $this->getLoggedUser();
        
        if(!($entity instanceof Activity) &&  $this->user)
        {
            $activity = new Activity();
            $activity->setUser($this->user);
            $activity->setAction(Activity::ACTION_CREATED);
            $activity->setEntityId($entity->getId());
            $activity->setEntityType(get_class($entity));
            $entityManager->persist($activity);
            $entityManager->flush();
        }
    }

    public function getLoggedUser()
    {
        $token = $this->security->getToken();
        if($token)
        {
            $user = $token->getUser();
            return $user && $user!='anon.' ? $user : false;
        }else{
            return false;
        }
    }

}
