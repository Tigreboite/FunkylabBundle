<?php

namespace Tigreboite\FunkylabBundle\Traits;

use Tigreboite\FunkylabBundle\Entity\User as User;

use Doctrine\ORM\Mapping as ORM;

trait Position
{
    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position = '999';

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
