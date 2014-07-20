<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})})
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $lastName
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @var boolean
     */
    private $isSendMail;

    public function __construct()
    {
        parent::__construct();
        $this->addRole("ROLE_ADMIN");
        $this->setEnabled(true);
        $this->setIsSendMail(true);
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isSuperAdmin(){
        return $this->hasRole("ROLE_SUPER_ADMIN");
    }

    /**
     * @param boolean $isSendMail
     */
    public function setIsSendMail($isSendMail)
    {
        $this->isSendMail = $isSendMail;
    }

    /**
     * @return boolean
     */
    public function getIsSendMail()
    {
        return $this->isSendMail;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $email
     * @return $this|void
     */
    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->firstName.' '.$this->lastName;
    }
}
