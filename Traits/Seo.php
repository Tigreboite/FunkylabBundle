<?php

namespace Tigreboite\FunkylabBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Seo
{
    /**
     * @ORM\Column(name="meta_title", type="string", nullable=true)
     */
    private $metaTitle;
    /**
     * @ORM\Column(name="meta_summary", type="string", nullable=true)
     */
    private $metaSummary;
    /**
     * @ORM\Column(name="meta_keywords", type="string", nullable=true)
     */
    private $metaKeywords;

    /**
     * Get metaKeywords.
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * Get metaTitle.
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * @param $metaTitle
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * @return mixed
     */
    public function getMetaSummary()
    {
        return $this->metaSummary;
    }

    /**
     * @param $metaSummary
     */
    public function setMetaSummary($metaSummary)
    {
        $this->metaSummary = $metaSummary;
    }
}
