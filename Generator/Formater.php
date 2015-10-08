<?php

namespace Tigreboite\FunkylabBundle\Generator;

abstract class Formater {
    protected $bundle;
    protected $entity;

    protected function __construct($bundle,$entity) {
        $this->bundle = $bundle;
        $this->entity = $entity;
    }

    protected function format(){}
}
