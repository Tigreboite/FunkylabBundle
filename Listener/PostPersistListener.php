<?php
namespace Tigreboite\FunkylabBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PostPersistListener
{
    private $container;
    private $mailer;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
//        $this->mailer = $this->container->get('appbundle.mailer');
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $router = $this->container->get('router');
        $entity = $args->getEntity();

        $entityManager = $args->getEntityManager();
//        dump('postUpdate');exit;

//        $this->index($args);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $router = $this->container->get('router');
        $entity = $args->getEntity();

        $entityManager = $args->getEntityManager();
//        dump('postDelete');exit;


//        $this->index($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $router = $this->container->get('router');
        $entity = $args->getEntity();

        $entityManager = $args->getEntityManager();
//        dump('postPersist');exit;

        //TODO : Catch event

        /*if ($entity instanceof Idea) {



        }else
        if ($entity instanceof IdeaComment) {


        }*/
    }

}
