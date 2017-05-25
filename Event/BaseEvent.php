<?php

namespace Tigreboite\FunkylabBundle\Event;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class BaseEvent extends Event
{
    private $name;
    private $container;
    private $request;

    /**
     * BaseEvent constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->request = $container->get('request_stack')->getCurrentRequest();
        $this->name = '';
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
