<?php

namespace Tigreboite\FunkylabBundle\Generator;

class FormaterChain
{
    private $formaters = [];

    public function __construct()
    {
        $this->formater = array();
    }

    /**
     * @param FormaterBase $formater
     */
    public function add(FormaterBase $formater)
    {
        $this->formaters[$formater->getType()] = $formater;
    }

    /**
     * @return array
     */
    public function getFormaters()
    {
        return $this->formaters;
    }

    /**
     * @param $formatName
     * @return bool
     */
    public function getFormater($formatName)
    {
        return isset($this->formaters[$formatName]) && !is_null($this->formaters[$formatName]) ? $this->formaters[$formatName] : false;
    }

}
