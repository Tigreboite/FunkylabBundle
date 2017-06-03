<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Tigreboite\FunkylabBundle\Traits\Blameable;
use Doctrine\Common\Collections\ArrayCollection;
use Tigreboite\FunkylabBundle\Traits\Publishable;

/**
 * @ORM\Table(name="flb_actuality_comment")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Repository\ActualityRepository")
 */
class ActualityComment
{
    use Blameable, Publishable;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @ORM\Column(name="text", type="string", nullable=true)
     */
    private $text;

    /**
     * @ORM\JoinColumn(name="actuality_id", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="Tigreboite\FunkylabBundle\Entity\Actuality", inversedBy="comments", cascade={"remove"})
     */
    private $actuality;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getActuality()
    {
        return $this->actuality;
    }

    /**
     * @param Actuality $actuality
     * @return mixed
     */
    public function setActuality(Actuality $actuality)
    {
        $this->actuality = $actuality;
        return $this;
    }

}
