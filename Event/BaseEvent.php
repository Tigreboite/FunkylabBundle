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
     */
    public function __construct()
    {
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
