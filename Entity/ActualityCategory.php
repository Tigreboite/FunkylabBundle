<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Tigreboite\FunkylabBundle\Traits\Blameable;
use Doctrine\Common\Collections\ArrayCollection;
use Tigreboite\FunkylabBundle\Traits\Publishable;

/**
 * @ORM\Table(name="flb_actuality_category")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Repository\ActualityRepository")
 */
class ActualityCategory
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
     * @ORM\Column(name="meta_title", type="string", nullable=true)
     */
    private $metaTitle;
    /**
     * @ORM\Column(name="meta_summary", type="string", nullable=true)
     */
    private $metaSummary;
    /**
     * @ORM\Column(name="meta_keywords", type="string", nullable=true)
     */
    private $metaKeywords;

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
     * Get metaTitle.
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaTitle.
     *
     * @param string $metaTitle
     *
     * @return Actuality
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaSummary.
     *
     * @return string
     */
    public function getMetaSummary()
    {
        return $this->metaSummary;
    }

    /**
     * Set metaSummary.
     *
     * @param string $metaSummary
     *
     * @return Actuality
     */
    public function setMetaSummary($metaSummary)
    {
        $this->metaSummary = $metaSummary;

        return $this;
    }

    /**
     * Get metaKeywords.
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set metaKeywords.
     *
     * @param string $metaKeywords
     *
     * @return Actuality
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
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
