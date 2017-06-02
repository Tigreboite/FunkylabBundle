<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait Image
{
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(time().uniqid(mt_rand(), true));
            $this->image = $filename.'.'.$this->getFile()->getClientOriginalExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $extensions = array('png', 'jpg', 'jpeg', 'gif', 'bmp');
        $ext = $this->getFile()->getClientOriginalExtension();
        if (!in_array($ext, $extensions)) {
            return;
        }

        // if there is an error whe n moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->image);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir().'/'.$this->image;
    }

    public function getWebPath()
    {
        $file = null === $this->image
            ? null
            : $this->getUploadDir().'/'.$this->image;

        $fileFullPath = ($file !== null) ?  $this->getUploadRootDir().'/'.$this->image : null;

        if ((null === $file || !file_exists($fileFullPath)) || is_dir($fileFullPath)) {
            return $this->IMAGE_DEFAULT;
        }

        return $file;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return $this->IMAGE_PATH;
    }

    public function getBaseNameImage($image)
    {
        $tmp = explode('/', $image);

        return $tmp[count($tmp) - 1];
    }
}
