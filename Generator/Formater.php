<?php

namespace Tigreboite\FunkylabBundle\Generator;

use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpClass;

abstract class Formater {

    protected $bundle;
    protected $entity;
    protected $type;

    public function __construct($bundle,$entity) {
        $this->bundle = $bundle;
        $this->entity = $entity;
    }

    private function format(){}

    public function getController()
    {
        $class = PhpClass::fromReflection(new \ReflectionClass('Tigreboite\\FunkylabBundle\\Generator\\Controller\\'.$this->type.'Controller'));

        $generator = new CodeGenerator();
//        $code = $generator->generate($class);
        return $generator;

    }
}
