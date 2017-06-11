<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Tigreboite\FunkylabBundle\Traits\Blameable;
use Doctrine\Common\Collections\ArrayCollection;
use Tigreboite\FunkylabBundle\Traits\Position;

/**
 * @ORM\Table(name="flb_bloc")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Repository\BlocRepository")
 */
class Bloc
{
    use Blameable, TimestampableEntity, Position;

    public $layouts = array(
        'Wysiwyg' => 'bloc-wysiwyg',
        'File' => 'bloc-file',
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
     * @var string
     *
     * @ORM\Column(name="layout", type="string", length=255, nullable=true)
     */
    private $layout;
    /**
     * @var string
     *
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    private $type;
    /**
     * @ORM\ManyToOne(targetEntity="Actuality", inversedBy="blocs")
     * @ORM\JoinColumn(name="actuality_id", referencedColumnName="id")
     */
    private $actuality;
    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="blocs")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;
    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", nullable=true)
     */
    private $file;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->position = 999;
    }

    public function __toString()
    {
        return $this->title ? $this->title : 'pas de titre';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Bloc
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActuality()
    {
        return $this->actuality;
    }

    /**
     * @param mixed $actuality
     */
    public function setActuality(Actuality $actuality)
    {
        $this->actuality = $actuality;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
