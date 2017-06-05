<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Tigreboite\FunkylabBundle\Traits\Blameable;
use Doctrine\Common\Collections\ArrayCollection;
use Tigreboite\FunkylabBundle\Traits\Publishable;
use Tigreboite\FunkylabBundle\Traits\Seo;

/**
 * @ORM\Table(name="flb_actuality_category")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Repository\ActualityRepository")
 */
class ActualityCategory
{
    use Blameable, TimestampableEntity, Seo;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    private $title;
    /**
     * @var string
     *
     * @ORM\Column(length=255, unique=true)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Tigreboite\FunkylabBundle\Entity\Actuality", mappedBy="category", cascade={"remove"})
     */
    private $actuality;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->actuality = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Actuality
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return Actuality
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getActuality()
    {
        return $this->actuality;
    }

    /**
     * @param Actuality $actuality
     */
    public function addActuality(Actuality $actuality)
    {
        $this->actuality->add($actuality);
        $actuality->setCategory($this);
    }

    /**
     * @param Actuality $actuality
     */
    public function removeActuality(Actuality $actuality)
    {
        $this->actuality->remove($actuality);
        $actuality->setCategory(null);
    }

}
