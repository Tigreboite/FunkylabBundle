<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Tigreboite\FunkylabBundle\Entity\QuestionnaireQuestion;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_7D93D64992FC23A8", columns={"username_canonical"}), @ORM\UniqueConstraint(name="UNIQ_7D93D649A0D96FBF", columns={"email_canonical"}), @ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})}, indexes={@ORM\Index(name="fk_user_languages1_idx", columns={"language_id"})})
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *     fields={"email"},
 *     message="email_exist"
 * )
 * @UniqueEntity("username")
 */
class User extends BaseUser
{
    use Image;

    public $IMAGE_PATH     = "medias/profile";
    public $IMAGE_DEFAULT  = "images/icons@2x/picto_user_default.png";

    const ROLE_MODERATOR  = 'ROLE_MODERATOR';
    const ROLE_BRAND      = 'ROLE_BRAND';
    const ROLE_USER       = 'ROLE_USER';
    const ROLE_ADMIN      = 'ROLE_ADMIN';


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="date", nullable=true)
     */
    protected $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="civility", type="string", length=5, nullable=true)
     */
    protected $civility;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    protected $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse2", type="string", length=255, nullable=true)
     */
    protected $adresse2;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=10, nullable=true)
     */
    protected $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var boolean
     *
     * @ORM\Column(name="newsletter", type="boolean", nullable=true)
     */
    protected $newsletter;

    /**
     * @var boolean
     *
     * @ORM\Column(name="newsletter_partner", type="boolean", nullable=true)
     */
    protected $newsletterPartner;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="connected_at", type="datetime", nullable=true)
     */
    protected $connectedAt;

    /**
     * @var \Pays
     *
     * @ORM\ManyToOne(targetEntity="Pays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    protected $country;

    /**
     * @var \Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    protected $language;

    /**
     * @var string
     *
     * @ORM\Column(name="oldpassword", type="string", length=255, nullable=true)
     */
    protected $oldpassword;

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
    public $file;

    /**
     * @Assert\Regex(
     *  pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *  message="Password must be seven or more characters long and contain at least one digit, one upper- and one lowercase character."
     * )
     */
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(length=128, unique=true)
     * @Gedmo\Slug(fields={"firstname", "lastname"}, separator="-")
     */
    private $slug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isarchived", type="boolean", nullable=true, options={"default" = 0})
     */
    private $isarchived;

    public function __construct()
    {
        parent::__construct();
        $this->setEnabled(true);
        $this->setCreatedAt(new \DateTime);
        $this->isarchived       = false;
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
     * Set dob
     *
     * @param \DateTime $dob
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set civility
     *
     * @param string $civility
     * @return User
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * Get civility
     *
     * @return string
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return User
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set adresse2
     *
     * @param string $adresse2
     * @return User
     */
    public function setAdresse2($adresse2)
    {
        $this->adresse2 = $adresse2;

        return $this;
    }

    /**
     * Get adresse2
     *
     * @return string
     */
    public function getAdresse2()
    {
        return $this->adresse2;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return User
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set oldpassword
     *
     * @param string $oldpassword
     * @return User
     */
    public function setOldpassword($oldpassword)
    {
        $this->oldpassword = $oldpassword;

        return $this;
    }

    /**
     * Get oldpassword
     *
     * @return string
     */
    public function getOldpassword()
    {
        return $this->oldpassword;
    }

    /**
     * Set decathlonCardId
     *
     * @param string $decathlonCardId
     * @return User
     */
    public function setDecathlonCardId($decathlonCardId)
    {
        $this->decathlonCardId = $decathlonCardId;

        return $this;
    }

    /**
     * Get decathlonCardId
     *
     * @return string
     */
    public function getDecathlonCardId()
    {
        return $this->decathlonCardId;
    }

    /**
     * Set nbPointsCurrent
     *
     * @param string $nbPointsCurrent
     * @return User
     */
    public function setNbPointsCurrent($nbPointsCurrent)
    {
        $this->nbPointsCurrent = $nbPointsCurrent;

        return $this;
    }

    /**
     * Get nbPointsCurrent
     *
     * @return string
     */
    public function getNbPointsCurrent()
    {
        return $this->nbPointsCurrent;
    }

    /**
     * Set nbPointsTotal
     *
     * @param integer $nbPointsTotal
     * @return User
     */
    public function setNbPointsTotal($nbPointsTotal)
    {
        $this->nbPointsTotal = $nbPointsTotal;

        return $this;
    }

    /**
     * Get nbPointsTotal
     *
     * @return integer
     */
    public function getNbPointsTotal()
    {
        return $this->nbPointsTotal;
    }

    /**
     * Set newsletter
     *
     * @param boolean $newsletter
     * @return User
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set cgu
     *
     * @param boolean $cgu
     * @return User
     */
    public function setCgu($cgu)
    {
        $this->cgu = $cgu;

        return $this;
    }

    /**
     * Get cgu
     *
     * @return boolean
     */
    public function getCgu()
    {
        return $this->cgu;
    }

    /**
     * Set newsletterPartner
     *
     * @param boolean $newsletterPartner
     * @return User
     */
    public function setNewsletterPartner($newsletterPartner)
    {
        $this->newsletterPartner = $newsletterPartner;

        return $this;
    }

    /**
     * Get newsletterPartner
     *
     * @return boolean
     */
    public function getNewsletterPartner()
    {
        return $this->newsletterPartner;
    }

    /**
     * Set idFacebook
     *
     * @param string $idFacebook
     * @return User
     */
    public function setIdFacebook($idFacebook)
    {
        $this->idFacebook = $idFacebook;

        return $this;
    }

    /**
     * Get idFacebook
     *
     * @return string
     */
    public function getIdFacebook()
    {
        return $this->idFacebook;
    }

    /**
     * Set idTwitter
     *
     * @param string $idTwitter
     * @return User
     */
    public function setIdTwitter($idTwitter)
    {
        $this->idTwitter = $idTwitter;

        return $this;
    }

    /**
     * Get idTwitter
     *
     * @return string
     */
    public function getIdTwitter()
    {
        return $this->idTwitter;
    }

    /**
     * Set idGoogleplus
     *
     * @param string $idGoogleplus
     * @return User
     */
    public function setIdGoogleplus($idGoogleplus)
    {
        $this->idGoogleplus = $idGoogleplus;

        return $this;
    }

    /**
     * Get idGoogleplus
     *
     * @return string
     */
    public function getIdGoogleplus()
    {
        return $this->idGoogleplus;
    }

    /**
     * Set idDecathlon
     *
     * @param string $idDecathlon
     * @return User
     */
    public function setIdDecathlon($idDecathlon)
    {
        $this->idDecathlon = $idDecathlon;

        return $this;
    }

    /**
     * Get idDecathlon
     *
     * @return string
     */
    public function getIdDecathlon()
    {
        return $this->idDecathlon;
    }

    /**
     * Set twitterOauth
     *
     * @param string $twitterOauth
     * @return User
     */
    public function setTwitterOauth($twitterOauth)
    {
        $this->twitterOauth = $twitterOauth;

        return $this;
    }

    /**
     * Get twitterOauth
     *
     * @return string
     */
    public function getTwitterOauth()
    {
        return $this->twitterOauth;
    }

    /**
     * Set twitterOauthSecret
     *
     * @param string $twitterOauthSecret
     * @return User
     */
    public function setTwitterOauthSecret($twitterOauthSecret)
    {
        $this->twitterOauthSecret = $twitterOauthSecret;

        return $this;
    }

    /**
     * Get twitterOauthSecret
     *
     * @return string
     */
    public function getTwitterOauthSecret()
    {
        return $this->twitterOauthSecret;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
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
     * Set country
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Pays $country
     * @return User
     */
    public function setCountry(\Tigreboite\FunkylabBundle\Entity\Pays $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Tigreboite\FunkylabBundle\Entity\Pays
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set language
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Language $language
     * @return User
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        if(!empty($email)) $this->setUsername($email);

        return $this;
    }

    /**
     * Add idea
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Idea
     * @return User
     */
    public function addFavorite(\Tigreboite\FunkylabBundle\Entity\Idea $idea)
    {
        $this->favorite[] = $idea;

        return $this;
    }

    /**
     * Get favorite
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * Remove favorite
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Idea
     * @return User
     */
    public function removeFavorite(\Tigreboite\FunkylabBundle\Entity\Idea $idea)
    {
        foreach($this->favorite as $k=>$v)
        {
            if($idea == $v)
            {
                unset($this->favorite[$k]);
            }
        }

        return $this;
    }

    /**
     * Get participation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * Add idea
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Idea
     * @return User
     */
    public function addParticipation(\Tigreboite\FunkylabBundle\Entity\Idea $idea)
    {
        $this->participation[] = $idea;

        return $this;
    }

    /**
     * Remove participation
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Idea
     * @return User
     */
    public function removeParticipation(\Tigreboite\FunkylabBundle\Entity\Idea $idea)
    {
        foreach($this->participation as $k=>$v)
        {
            if($idea == $v)
            {
                unset($this->participation[$k]);
            }
        }

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getIdea()
    {
        return $this->idea;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set connectedAt
     *
     * @param \DateTime $connectedAt
     * @return User
     */
    public function setConnectedAt($connectedAt)
    {
        $this->connectedAt = $connectedAt;

        return $this;
    }

    /**
     * Get connectedAt
     *
     * @return \DateTime
     */
    public function getConnectedAt()
    {
        return $this->connectedAt;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return User
     */
    public function setImage($image)
    {
        $this->image = str_replace($this->getUploadDir(), '', $image);

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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getFirstname()." ".$this->getLastname();
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return User
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
        return $this->slug;
    }

    /**
     * Set ref_cust
     *
     * @param integer $refCust
     * @return User
     */
    public function setRefCust($refCust)
    {
        $this->ref_cust = $refCust;

        return $this;
    }

    /**
     * Get ref_cust
     *
     * @return integer
     */
    public function getRefCust()
    {
        return $this->ref_cust;
    }

    /**
     * Set code_parrain
     *
     * @param integer $codeParrain
     * @return User
     */
    public function setCodeParrain($codeParrain)
    {
        $this->code_parrain = $codeParrain;

        return $this;
    }

    /**
     * Get code_parrain
     *
     * @return integer
     */
    public function getCodeParrain()
    {
        return $this->code_parrain;
    }

    /**
     * Set isarchived
     *
     * @param boolean $isarchived
     * @return User
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
