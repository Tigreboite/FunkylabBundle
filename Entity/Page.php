<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Brand
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Entity\PageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Page
{
    use Image;

    public $IMAGE_DEFAULT = "images/illustrations/idea-img.jpg";
    public $IMAGE_PATH    = "images/backgrounds/pages";

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     */
    protected $children;

    /**
     * @var \Page
     *
     * @ORM\ManyToOne(targetEntity="Page")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100, nullable=false)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $image;

    /**
     * @Assert\File(
     *     maxSize = "6000000",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png"},
     * )
     */
    public $file;


    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Brand
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->getParent() ? $this->getParent()->getSlug() : $this->slug;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Brand
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set image
     *
     * @param string $slug
     * @return Sport
     */
    public function setImage($image)
    {
        $this->image = $this->getBaseNameImage($image);

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set user
     *
     * @param \Tigreboite\FunkylabBundle\Entity\User $user
     * @return Idea
     */
    public function setUser(\Tigreboite\FunkylabBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tigreboite\FunkylabBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set parent
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Page $parent
     * @return Page
     */
    public function setParent(\Tigreboite\FunkylabBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Tigreboite\FunkylabBundle\Entity\Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Page $children
     * @return Idea
     */
    public function addChildren(\Tigreboite\FunkylabBundle\Entity\Page $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Page $children
     */
    public function removeChildren(\Tigreboite\FunkylabBundle\Entity\Page $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Set language
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Language $language
     * @return Idea
     */
    public function setLanguage(\Tigreboite\FunkylabBundle\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Tigreboite\FunkylabBundle\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Add children
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Page $children
     * @return Page
     */
    public function addChild(\Tigreboite\FunkylabBundle\Entity\Page $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Page $children
     */
    public function removeChild(\Tigreboite\FunkylabBundle\Entity\Page $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }



}
