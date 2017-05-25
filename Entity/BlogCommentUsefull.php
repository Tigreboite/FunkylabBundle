<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CommentUsefull
 *
 * @ORM\Table(name="flb_blog_comment_usefull")
 * @ORM\Entity
 */
class BlogCommentUsefull
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="BlogComment", inversedBy="commentusefull")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="blogcomment_id", referencedColumnName="id")
     * })
     */
    private $blogcomment;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isusefull", type="boolean")
     */
    private $isusefull;

     /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

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
     * Set ideacommentId
     *
     * @param integer $ideacommentId
     * @return CommentUsefull
     */
    public function setBlogcommentId($blogcommentId)
    {
        $this->blogcommentId = $blogcommentId;

        return $this;
    }

    /**
     * Get blogcommentId
     *
     * @return integer 
     */
    public function getBlogcommentId()
    {
        return $this->blogcommentId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return CommentUsefull
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set isusefull
     *
     * @param boolean $isusefull
     * @return CommentUsefull
     */
    public function setIsusefull($isusefull)
    {
        $this->isusefull = $isusefull;

        return $this;
    }

    /**
     * Get isusefull
     *
     * @return boolean 
     */
    public function getIsusefull()
    {
        return $this->isusefull;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return CommentUsefull
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
     * Set user
     *
     * @param \Tigreboite\FunkylabBundle\Entity\User $user
     * @return BlogCommentUsefull
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return CommentUsefull
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set blogcomment
     *
     * @param \Tigreboite\FunkylabBundle\Entity\IdeaComment $blogcomment
     * @return BlogCommentUsefull
     */
    public function setBlogcomment(\Tigreboite\FunkylabBundle\Entity\BlogComment $blogcomment = null)
    {
        $this->blogcomment = $blogcomment;

        return $this;
    }

    /**
     * Get blogcomment
     *
     * @return \Tigreboite\FunkylabBundle\Entity\BlogComment
     */
    public function getIdeacomment()
    {
        return $this->blogcomment;
    }

    /**
     * Get blogcomment
     *
     * @return \Tigreboite\FunkylabBundle\Entity\BlogComment 
     */
    public function getBlogcomment()
    {
        return $this->blogcomment;
    }
}
