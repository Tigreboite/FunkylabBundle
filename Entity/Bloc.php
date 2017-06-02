<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Tigreboite\FunkylabBundle\Traits\Blameable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="flb_bloc")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Repository\BlocRepository")
 */
class Bloc
{
    use Blameable;

    public $layouts = array(
        'bloc-wysiwyg' => 'WYSIWYG',
        'bloc-quote' => 'Quote',
        'bloc-h2' => 'H2',
        'bloc-file' => 'File',
        'bloc-image' => 'Wide image',
        'bloc-bref' => 'En bref',
    );
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
     * @ORM\Column(name="is_onsidebar", type="boolean", nullable=true)
     */
    private $onsidebar;
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
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer", nullable=true)
     */
    private $ordre = '999';
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
        $this->ordre = 999;
        $this->media = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title ? $this->title : 'pas de titre';
    }

    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
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

    /**
     * @return mixed
     */
    public function isOnsidebar()
    {
        return $this->onsidebar;
    }

    /**
     * @param mixed $onsidebar
     */
    public function setOnsidebar($onsidebar)
    {
        $this->onsidebar = $onsidebar;
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
}
