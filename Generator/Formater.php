<?php

namespace Tigreboite\FunkylabBundle\Generator;

use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpClass;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;

abstract class Formater {

    protected $bundle;
    protected $entity;
    protected $type;

    public function __construct($bundle,$entity)
    {
        $this->bundle       = $bundle;
        $this->entity       = $entity;
        $this->entityName   = explode('\\',$entity);
        $this->entityName   = end($this->entityName);
        $this->annotations  = $this->processAnnotation();
    }

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

    private function processAnnotation()
    {
        $annotations = array(
            'variables'=>array(),
            'variables_annotations'=>array(),
            'methodes'=>array(),
            'methodes_annotations'=>array(),
        );
        $annotationReader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($this->entity);

        // Class Annotations
        $classAnnotations = $annotationReader->getClassAnnotations($reflectionClass);

        // fields Annotations
        foreach($reflectionClass->getProperties() as $reflectionProperty)
        {
            $variable = new \ReflectionProperty($this->entity, $reflectionProperty->getName());
            if(!empty($variable))
                $annotations['variables'][]=$variable;
            $variable_annotations = $annotationReader->getPropertyAnnotations($variable);
            if(!empty($variable_annotations))
                $annotations['variables_annotations'][]=$variable_annotations;
        }

        // Methods Annotations
        foreach($reflectionClass->getMethods() as $reflectionMethod)
        {
            $method = new \ReflectionMethod($this->entity, $reflectionMethod->getName());
            if(!empty($method))
                $annotations['methodes'][]=$method;
            $method_annotations = $annotationReader->getMethodAnnotations($method);
            if(!empty($method_annotations))
                $annotations['methodes_annotations'][]=$method_annotations;
        }

        return $annotations;

    }
}
