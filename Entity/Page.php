<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Tigreboite\FunkylabBundle\Traits\Blameable;

/**
 * @ORM\Table(name="flb_page")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Entity\BaseRepository")
 */
class Page
{
    use TimestampableEntity, Blameable;

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
     * @ORM\Column(name="summary", type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(name="slug", type="string", nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private $image;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Bloc", mappedBy="page", cascade={"remove"})
     * @ORM\OrderBy({"ordre" = "ASC"})
     */
    private $blocs;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->blocs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title.
     *
     * @param string $title
     *
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * Set summary.
     *
     * @param string $summary
     *
     * @return Page
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary.
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
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
     * Set image.
     *
     * @param string $image
     *
     * @return Page
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set metaTitle.
     *
     * @param string $metaTitle
     *
     * @return Page
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

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
     * Set metaSummary.
     *
     * @param string $metaSummary
     *
     * @return Page
     */
    public function setMetaSummary($metaSummary)
    {
        $this->metaSummary = $metaSummary;

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
     * Set metaKeywords.
     *
     * @param string $metaKeywords
     *
     * @return Page
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

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
     * Add bloc.
     *
     * @param Bloc $bloc
     *
     * @return Actuality
     */
    public function addBloc(Bloc $bloc)
    {
        $bloc->setAdvice($this);
        $this->blocs->add($bloc);

        return $this;
    }

    /**
     * Remove bloc.
     *
     * @param Bloc $bloc
     */
    public function removeBloc(Bloc $bloc)
    {
        $this->blocs->removeElement($bloc);
    }

    /**
     * Get bloc.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlocs()
    {
        return $this->blocs;
    }

    public function hasManager()
    {
        return false;
    }
}
