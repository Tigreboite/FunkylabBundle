<?php

namespace Tigreboite\FunkylabBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User.
 *
 * @ORM\Table(name="flb_user", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_7D93D64992FC23A8", columns={"username_canonical"}), @ORM\UniqueConstraint(name="UNIQ_7D93D649A0D96FBF", columns={"email_canonical"}), @ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})}, indexes={@ORM\Index(name="fk_user_languages1_idx", columns={"language_id"})})
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
    const ROLE_MODERATOR = 'ROLE_MODERATOR';
    const ROLE_BRAND = 'ROLE_BRAND';
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var int
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
     * @var bool
     *
     * @ORM\Column(name="newsletter", type="boolean", nullable=true)
     */
    protected $newsletter;

    /**
     * @var bool
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
     * @var Pays
     *
     * @ORM\ManyToOne(targetEntity="Pays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    protected $country;

    /**
     * @var Language
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
     * @var bool
     *
     * @ORM\Column(name="isarchived", type="boolean", nullable=true, options={"default" = 0})
     */
    private $isarchived;

    /**
     * @ORM\Column(name="company", type="string", length=255)
     */
    private $company;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    public function __construct()
    {
        parent::__construct();
        $this->setEnabled(true);
        $this->setCreatedAt(new \DateTime());
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
     * Set dob.
     *
     * @param \DateTime $dob
     *
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob.
     *
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set civility.
     *
     * @param string $civility
     *
     * @return User
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * Get civility.
     *
     * @return string
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set adresse.
     *
     * @param string $adresse
     *
     * @return User
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set adresse2.
     *
     * @param string $adresse2
     *
     * @return User
     */
    public function setAdresse2($adresse2)
    {
        $this->adresse2 = $adresse2;

        return $this;
    }

    /**
     * Get adresse2.
     *
     * @return string
     */
    public function getAdresse2()
    {
        return $this->adresse2;
    }

    /**
     * Set zipcode.
     *
     * @param string $zipcode
     *
     * @return User
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode.
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set avatar.
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set oldpassword.
     *
     * @param string $oldpassword
     *
     * @return User
     */
    public function setOldpassword($oldpassword)
    {
        $this->oldpassword = $oldpassword;

        return $this;
    }

    /**
     * Get oldpassword.
     *
     * @return string
     */
    public function getOldpassword()
    {
        return $this->oldpassword;
    }

    /**
     * Set newsletter.
     *
     * @param bool $newsletter
     *
     * @return User
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter.
     *
     * @return bool
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set newsletterPartner.
     *
     * @param bool $newsletterPartner
     *
     * @return User
     */
    public function setNewsletterPartner($newsletterPartner)
    {
        $this->newsletterPartner = $newsletterPartner;

        return $this;
    }

    /**
     * Get newsletterPartner.
     *
     * @return bool
     */
    public function getNewsletterPartner()
    {
        return $this->newsletterPartner;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set country.
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Pays $country
     *
     * @return User
     */
    public function setCountry(\Tigreboite\FunkylabBundle\Entity\Pays $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return \Tigreboite\FunkylabBundle\Entity\Pays
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set language.
     *
     * @param \Tigreboite\FunkylabBundle\Entity\Language $language
     *
     * @return User
     */
    public function setLanguage(\Tigreboite\FunkylabBundle\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language.
     *
     * @return \Tigreboite\FunkylabBundle\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        if (!empty($email)) {
            $this->setUsername($email);
        }

        return $this;
    }

    /**
     * Set connectedAt.
     *
     * @param \DateTime $connectedAt
     *
     * @return User
     */
    public function setConnectedAt($connectedAt)
    {
        $this->connectedAt = $connectedAt;

        return $this;
    }

    /**
     * Get connectedAt.
     *
     * @return \DateTime
     */
    public function getConnectedAt()
    {
        return $this->connectedAt;
    }

    /**
     * Set image.
     *
     * @param string $image
     *
     * @return User
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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getFirstname().' '.$this->getLastname();
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return User
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
     * Set isarchived.
     *
     * @param string $isarchived
     *
     * @return User
     */
    public function setIsarchived($isarchived)
    {
        $this->isarchived = $isarchived;

        return $this;
    }

    /**
     * Get isarchived.
     *
     * @return string
     */
    public function getIsarchived()
    {
        return $this->isarchived;
    }

    /**
     * Set company.
     *
     * @param string $company
     *
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return User
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
     * Set phone.
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function getCSVData()
    {
        return array();
    }
}
