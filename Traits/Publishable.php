<?php

namespace Tigreboite\FunkylabBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Publishable
{
    /**
     * @ORM\Column(name="is_published", type="boolean", nullable=true)
     */
    private $published;

    /**
     * @return mixed
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }
}
