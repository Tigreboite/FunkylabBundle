<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaqCategory
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Entity\ActivityRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Activity
{
    private $em;

    const STATUT_UNSEEN                 = 0;
    const STATUT_PUBLISHED              = 1;
    const STATUT_ARCHIVED               = 2;

    const TYPE_BLOG                     = "blog";
    const TYPE_BLOG_COMMENT             = "blog_comment";
    const TYPE_IDEA                     = "idea";
    const TYPE_IDEA_COMMENT             = "idea_comment";
    const TYPE_USER                     = "user";
    const TYPE_QUESTION                 = "question";
    const TYPE_ABUSE                    = "abuse";

    const USER_IDEA_CREATED             = "USER_IDEA_CREATED";
    const USER_IDEA_UPDATED             = "USER_IDEA_UPDATED";
    const USER_IDEA_COMMENT             = "USER_IDEA_COMMENT";
    const USER_IDEA_QUESTION            = "USER_IDEA_QUESTION";
    const USER_BLOG_COMMENT             = "USER_BLOG_COMMENT";
    const USER_ABUSE                    = "USER_ABUSE";

    public $statutString = array(
        'Unseen',
        'Published',
        'Archived'
    );

    private $activities = array(
      'USER_IDEA_CREATED'             => "!user submited a new idea !idea",
      'USER_IDEA_UPDATED'             => "!user updated an idea !idea",
      'USER_IDEA_COMMENT'             => "!user posted a comment !comment on the idea !idea",
      'USER_IDEA_QUESTION'            => "!user answered !answer to the question !question of the idea !idea",
      'USER_BLOG_COMMENT'             => "!user posted   a comment !comment on the blog page !blog",
      'USER_ABUSE'                    => "!user reported an abuse !abuse on the idea !idea",
    );

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    protected $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="activity", type="string", length=255, nullable=false)
     */
    private $activity;

    /**
     * @var string
     *
     * @ORM\Column(name="entity_type", type="string", length=255, nullable=false)
     */
    private $entityType;

    /**
     * @var integer
     *
     * @ORM\Column(name="statut", type="integer", nullable=false, options={"default" = 0})
     */
    private $statut;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User",inversedBy="activity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $language;


    public function __construct()
    {
        $this->setCreatedAt(new \DateTime);
        $this->setStatut(self::STATUT_UNSEEN);
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
     * Set entityType
     *
     * @return Activity
     */
    public function setEntityType($entityType = "")
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set activity
     *
     * @return Activity
     */
    public function setActivity($activity = "")
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return string
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set entityId
     *
     * @return Activity
     */
    public function setEntityId($entityId = false)
    {
        $this->entityId = $entityId;

        return $this;
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
     * Get entityId
     *
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @return string
     */
    public function getStringActivity()
    {
        return isset($this->activities[$this->getActivity()]) ? $this->activities[$this->getActivity()] : false;
    }

    public function getEntity()
    {
        switch($this->getEntityType())
        {
            case self::TYPE_IDEA:
                return $this->em->getRepository('TigreboiteFunkylabBundle:Idea')->find($this->getEntityId());
                break;
            case self::TYPE_BLOG:
                return $this->em->getRepository('TigreboiteFunkylabBundle:Blog')->find($this->getEntityId());
                break;
            case self::TYPE_USER:
                return $this->em->getRepository('TigreboiteFunkylabBundle:User')->find($this->getEntityId());
                break;
            case self::TYPE_ABUSE:
                return $this->em->getRepository('TigreboiteFunkylabBundle:Abuse')->find($this->getEntityId());
                break;
            case self::TYPE_IDEA_COMMENT:
                return $this->em->getRepository('TigreboiteFunkylabBundle:IdeaComment')->find($this->getEntityId());
                break;
            case self::TYPE_BLOG_COMMENT:
                return $this->em->getRepository('TigreboiteFunkylabBundle:BlogComment')->find($this->getEntityId());
                break;
            case self::TYPE_QUESTION:
                return $this->em->getRepository('TigreboiteFunkylabBundle:QuestionnaireQuestionReponse')->find($this->getEntityId());
                break;
            default:
                return false;
        }
    }

    /**
     * @param bool $em
     * @return $this
     */
    public function setEntityManager($em=false)
    {
        if($em)
            $this->em=$em;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }

    /**
     * Set statut
     *
     * @return Activity
     */
    public function setStatut($statut = 0)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
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

}
