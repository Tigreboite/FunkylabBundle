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
 * @ORM\Table(name="flb_actuality")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Repository\ActualityRepository")
 */
class Actuality
{
    use Blameable, Publishable, Seo, TimestampableEntity;

    public $categories = array(
        'news' => 'News',
        'study' => 'Study',
        'discover' => 'Discover'
    );

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
     * @ORM\Column(name="mea", type="boolean", nullable=true)
     */
    private $mea;
    /**
     * @ORM\Column(name="date_start", type="datetime", nullable=true)
     */
    private $dateStart;
    /**
     * @ORM\Column(name="date_end", type="datetime", nullable=true)
     */
    private $dateEnd;
    /**
     * @var string
     *
     * @ORM\Column(length=255, unique=true)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Bloc", mappedBy="actuality", cascade={"remove"})
     * @ORM\OrderBy({"ordre" = "ASC"})
     */
    private $blocs;
    /**
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private $image;
    /**
     * @ORM\ManyToOne(targetEntity="Tigreboite\FunkylabBundle\Entity\ActualityCategory", inversedBy="actuality")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="Tigreboite\FunkylabBundle\Entity\ActualityComment", mappedBy="actuality")
     */
    private $comments;

    /**
     * @ORM\Column(name="tags", type="string", nullable=true)
     */
    private $tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->blocs = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->dateStart = new \DateTime();
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
     * Get summary.
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set summary.
     *
     * @param string $summary
     *
     * @return Actuality
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

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
     * Add bloc.
     *
     * @param Bloc $bloc
     *
     * @return Actuality
     */
    public function addBloc(Bloc $bloc)
    {
        $bloc->setActuality($this);
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

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param ActualityCategory $category
     */
    public function setCategory(ActualityCategory $category)
    {
        $category->addActuality($this);
        $this->category = $category;
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
     * Set image.
     *
     * @param string $image
     *
     * @return Actuality
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get dateStart.
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateStart.
     *
     * @param \DateTime $dateStart
     *
     * @return Actuality
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateEnd.
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set dateEnd.
     *
     * @param \DateTime $dateEnd
     *
     * @return Actuality
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get tags.
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags.
     *
     * @param string $tags
     *
     * @return Actuality
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get mea.
     *
     * @return bool
     */
    public function getMea()
    {
        return $this->mea;
    }

    /**
     * Set mea.
     *
     * @param bool $mea
     *
     * @return Actuality
     */
    public function setMea($mea)
    {
        $this->mea = $mea;

        return $this;
    }

    /**
     * Get mea.
     *
     * @return bool
     */
    public function isMea()
    {
        return $this->mea;
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
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param ActualityComment $comments
     */
    public function addActuality(ActualityComment $comments)
    {
        $this->comments->add($comments);
        $comments->setActuality($this);
    }

    /**
     * @param ActualityComment $comments
     */
    public function removeActuality(ActualityComment $comments)
    {
        $this->comments->remove($comments);
        $comments->setActuality(null);
    }
}
