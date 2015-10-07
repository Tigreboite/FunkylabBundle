<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlogComment
 *
 * @ORM\Table(name="blog_comment", indexes={@ORM\Index(name="fk_blog_comment_language1_idx", columns={"language_id"}), @ORM\Index(name="fk_blog_comment_iead1_idx", columns={"blog_id"}), @ORM\Index(name="fk_blog_comment_user1_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Entity\BlogCommentRepository")
 */
class BlogComment
{
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
     * @ORM\Column(name="comment", type="text", nullable=false)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \Blog
     *
     * @ORM\ManyToOne(targetEntity="Blog", inversedBy="blogcomments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="blog_id", referencedColumnName="id")
     * })
     */
    protected $blog;

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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_parent", type="integer", nullable=true)
     */
    private $id_parent;

    /**
     * @ORM\OneToMany(targetEntity="BlogCommentUsefull", mappedBy="blogcomment")
     */
    protected $commentusefull;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isarchived", type="boolean", nullable=true, options={"default" = 0})
     */
    private $isarchived;


    public function __construct()
    {
        $this->setCreatedAt(new \DateTime);
        $this->commentusefull = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->comment;
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
     * Set comment
     *
     * @param string $comment
     * @return BlogComment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return htmlentities(strip_tags($this->comment));
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return BlogComment
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
     * Set blog
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Blog $blog
     * @return BlogComment
     */
    public function setBlog(\Tigreboite\FunkylabBundle\Entity\Blog $blog = null)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog
     *
     * @return \Tigreboite\FunkylabBundle\Entity\Blog 
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * Set language
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Language $language
     * @return BlogComment
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
     * Set user
     *
     * @param \Tigreboite\FunkylabBundle\Entity\User $user
     * @return BlogComment
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
     * Set id_parent
     *
     * @param integer $idParent
     * @return BlogComment
     */
    public function setIdParent($idParent)
    {
        $this->id_parent = $idParent;

        return $this;
    }

    /**
     * Get id_parent
     *
     * @return integer 
     */
    public function getIdParent()
    {
        return $this->id_parent;
    }

    /**
     * Add commentusefull
     *
     * @param \Tigreboite\FunkylabBundle\Entity\BlogCommentUsefull $commentusefull
     * @return IdeaComment
     */
    public function addCommentusefull(\Tigreboite\FunkylabBundle\Entity\BlogCommentUsefull $commentusefull)
    {
        $this->commentusefull[] = $commentusefull;

        return $this;
    }

    /**
     * Remove commentusefull
     *
     * @param \Tigreboite\FunkylabBundle\Entity\BlogCommentUsefull $commentusefull
     */
    public function removeCommentusefull(\Tigreboite\FunkylabBundle\Entity\BlogCommentUsefull $commentusefull)
    {
        $this->commentusefull->removeElement($commentusefull);
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCommentusefull()
    {
        return $this->commentusefull;
    }

    /**
     * Set isarchived
     *
     * @param boolean $isarchived
     * @return Idea
     */
    public function setIsarchived($isarchived)
    {
        $this->isarchived = $isarchived;

        return $this;
    }

    /**
     * Get isarchived
     *
     * @return boolean
     */
    public function getIsarchived()
    {
        return $this->isarchived;
    }
}
