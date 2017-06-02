<?php

namespace Tigreboite\FunkylabBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class EntityEvent extends Event
{
    private $entity;

    /**
     * EntityEvent constructor.
     * @param $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}
