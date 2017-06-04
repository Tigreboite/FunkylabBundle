<?php

namespace Tigreboite\FunkylabBundle\Traits;

use Tigreboite\FunkylabBundle\Entity\User as User;

use Doctrine\ORM\Mapping as ORM;

trait Blameable
{
    /**
     * @var User
     *
     * @\Gedmo\Mapping\Annotation\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Tigreboite\FunkylabBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private $createdBy;

    /**
     * @var User
     *
     * @\Gedmo\Mapping\Annotation\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Tigreboite\FunkylabBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     */
    private $updatedBy;

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param User $updatedBy
     */
    public function setUpdatedBy(User $updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }
}
