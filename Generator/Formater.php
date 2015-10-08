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
        $this->entityName = explode('\\',$entity);
        $this->entityName = end($this->entityName);
    }

    private function format(){}

    public function getController()
    {
        $classController = 'Tigreboite\\FunkylabBundle\\Generator\\Controller\\'.$this->type.'Controller';
        dump($classController);

        $class = PhpClass::fromReflection(new \ReflectionClass($classController));

        $class->setQualifiedName($this->bundle.'\\Controller\\'.$this->entityName.'Controller');
        $class->addUseStatement($this->entity);
        $class->addUseStatement($this->entity."Type");

        $generator = new CodeGenerator();
        $code = $generator->generate($class);

        $code = str_replace('Datagrid',$this->entityName,$code);
        $code = str_replace('%entity_name%',$this->entityName,$code);
        $code = str_replace('%class_name%',strtolower($this->entityName),$code);

        return $code;
    }

    public function getViews()
    {

    }
}
