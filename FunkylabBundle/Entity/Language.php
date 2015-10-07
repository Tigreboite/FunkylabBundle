<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Entity\LanguageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Language
{

    use Image;
    public $IMAGE_PATH    = "medias/language";
    public $IMAGE_DEFAULT = "images/illustrations/illu_project_default.jpg";

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
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isenable", type="boolean", nullable=true, options={"default" = 0})
     */
    private $isenable;

    /**
     * @var string
     *
     * @ORM\Column(name="flag", type="string", length=45, nullable=true)
     */
    private $flag;

    /**
     * @ORM\Column(name="pdf_payment", type="string", length=255, nullable=true)
     */
    public $pdf_payment;

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
     * Set code
     *
     * @param string $code
     * @return Language
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set flag
     *
     * @param string $flag
     * @return Language
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return string
     */
    public function getFlag()
    {
        return $this->flag;
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Language
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isenable
     *
     * @param string $isenable
     * @return Language
     */
    public function setIsenable($isenable)
    {
        $this->isenable = $isenable;

        return $this;
    }

    /**
     * Get isenable
     *
     * @return string
     */
    public function getIsenable()
    {
        return $this->isenable;
    }


    /**
     * Set file_payment
     *
     * @param string $filePayment
     * @return Language
     */
    public function setFilePayment($filePayment)
    {
        $this->file_payment = $filePayment;

        return $this;
    }

    /**
     * Get file_payment
     *
     * @return string
     */
    public function getFilePayment()
    {
        return $this->file_payment;
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
     * Set path
     *
     * @param string $path
     * @return Language
     */
    public function setPath($path)
    {
        $this->path_payment = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path_payment;
    }


    /**
     * Set pdf_payment
     *
     * @param string $pdfPayment
     * @return Language
     */
    public function setPdfPayment($pdfPayment)
    {
        $this->pdf_payment = $pdfPayment;

        return $this;
    }

    /**
     * Get pdf_payment
     *
     * @return string
     */
    public function getPdfPayment()
    {
        return $this->pdf_payment;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Idea
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


}
