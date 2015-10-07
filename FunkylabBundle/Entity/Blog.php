<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Brand
 *
 * @ORM\Table(name="blog")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Entity\BlogRepository")
 */
class Blog
{
    use Image;

    public $IMAGE_PATH    = "medias/blog";
    public $IMAGE_DEFAULT = "images/illustrations/illu_project_default.jpg";

    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_ARCHIVED = 2;

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
     * @ORM\OneToMany(targetEntity="Blog", mappedBy="parent")
     */
    protected $children;

    /**
     * @var \Blog
     *
     * @ORM\ManyToOne(targetEntity="Blog", inversedBy="children")
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Assert\File(
     *     maxSize = "6000000",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png"},
     * )
     */
    private $file;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
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
     * @var \BlogType
     *
     * @ORM\ManyToOne(targetEntity="BlogType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="blog_type_id", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", options={"default" = 1})
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_published", type="datetime", nullable=true)
     */
    private $datePublished;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_unpublished", type="datetime", nullable=true)
     */
    private $dateUnpublished;

    /**
     * @ORM\OneToMany(targetEntity="BlogComment", mappedBy="blog")
     */
    protected $blogcomments;

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = self::STATUS_PUBLISHED;
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
     * @return Blog
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
     * @return Blog
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
     * @param string $image
     * @return Blog
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
     * @return Blog
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
     * @param \Tigreboite\FunkylabBundle\Entity\Blog $parent
     * @return Blog
     */
    public function setParent(\Tigreboite\FunkylabBundle\Entity\Blog $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Tigreboite\FunkylabBundle\Entity\Blog
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Blog $children
     * @return Blog
     */
    public function addChildren(\Tigreboite\FunkylabBundle\Entity\Blog $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Blog $children
     */
    public function removeChildren(\Tigreboite\FunkylabBundle\Entity\Blog $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Set language
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Language $language
     * @return Blog
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

    /**
     * Set type
     *
     * @param \Tigreboite\FunkylabBundle\Entity\BlogType $type
     * @return Blog
     */
    public function setType(\Tigreboite\FunkylabBundle\Entity\BlogType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Tigreboite\FunkylabBundle\Entity\BlogType
     */
    public function getType()
    {
        return $this->type;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Add children
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Blog $children
     * @return Blog
     */
    public function addChild(\Tigreboite\FunkylabBundle\Entity\Blog $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Blog $children
     */
    public function removeChild(\Tigreboite\FunkylabBundle\Entity\Blog $children)
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

    /**
     * Sets the value of status.
     *
     * @param integer $status the status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the value of status.
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets the value of datePublished.
     *
     * @return \DateTime
     */
    public function getDatePublished()
    {
        return $this->datePublished;
    }

    /**
     * Sets the value of datePublished.
     *
     * @param \DateTime $datePublished the date published
     *
     * @return self
     */
    public function setDatePublished($datePublished)
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    /**
     * Gets the value of dateUnpublished.
     *
     * @return \DateTime
     */
    public function getDateUnpublished()
    {
        return $this->dateUnpublished;
    }

    /**
     * Sets the value of dateUnpublished.
     *
     * @param \DateTime $dateUnpublished the date unpublished
     *
     * @return self
     */
    public function setDateUnpublished($dateUnpublished)
    {
        $this->dateUnpublished = $dateUnpublished;

        return $this;
    }

    /**
     * Add blogcomments
     *
     * @param \Tigreboite\FunkylabBundle\Entity\BlogComment $blogcomments
     * @return Blog
     */
    public function addBlogcomment(\Tigreboite\FunkylabBundle\Entity\BlogComment $blogcomments)
    {
        $this->blogcomments[] = $blogcomments;

        return $this;
    }

    /**
     * Remove blogcomments
     *
     * @param \Tigreboite\FunkylabBundle\Entity\BlogComment $blogcomments
     */
    public function removeBlogcomment(\Tigreboite\FunkylabBundle\Entity\BlogComment $blogcomments)
    {
        $this->blogcomments->removeElement($blogcomments);
    }

    /**
     * Get blogcomments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogcomments()
    {
        return $this->blogcomments;
    }
}
