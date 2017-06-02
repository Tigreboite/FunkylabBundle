<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use  Tigreboite\FunkylabBundle\Traits\Blameable;

/**
 * @ORM\Table(name="flb_actuality")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Repository\ActualityRepository")
 */
class Actuality
{
    use TimestampableEntity, Blameable;

    public $categories = array(
      'articles' => 'Articles',
      'etudes' => 'Etudes',
      'interviews' => 'Interviews',
      'case-studies' => 'Case studies',
      'news' => 'News',
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
     * @ORM\Column(name="cta", type="string", nullable=true)
     */
    private $cta;

    /**
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(name="category", type="string", nullable=true)
     */
    private $category;

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
     * @ORM\Column(name="tags", type="string", nullable=true)
     */
    private $tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->blocs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateStart = new \DateTime();
    }

    public function __toString()
    {
        return $this->getTitle();
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
     * @return Actuality
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
     * @return Actuality
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
     * @return Actuality
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
     * @return Actuality
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
     * Set cta.
     *
     * @param string $cta
     *
     * @return Actuality
     */
    public function setCta($cta)
    {
        $this->cta = $cta;

        return $this;
    }

    /**
     * Get cta.
     *
     * @return string
     */
    public function getCta()
    {
        return $this->cta;
    }

    /**
     * Set category.
     *
     * @param string $category
     *
     * @return Actuality
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get category.
     *
     * @return string
     */
    public function getCategoryText()
    {
        return isset($this->categories[$this->category]) ? $this->categories[$this->category] : '-';
    }

    public function getSplittedCategory()
    {
        $category = $this->getCategoryText();
        $spacepos = strpos($category, ' ');

        $first = $category;
        $second = '';

        if ($spacepos !== false) {
            $first = substr($category, 0, $spacepos);
            $second = trim(substr($category, $spacepos));
        }

        return array(
            'first' => $first,
            'second' => $second,
        );
    }

    /**
     * Get category.
     *
     * @return string
     */
    public function getCategoryHtml()
    {
        return isset($this->categories[$this->category]) ? $this->categories[$this->category] : '-';
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
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
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
     * Get dateStart.
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
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
     * Get dateEnd.
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set tags.
     *
     * @param string $tags
     *
     * @return Advice
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

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
    public function getMea()
    {
        return $this->mea;
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
     * Get metaKeywords.
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }
}
