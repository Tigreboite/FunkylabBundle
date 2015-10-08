<?php

namespace Tigreboite\FunkylabBundle\Generator;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;

abstract class Formater {

    protected $bundle;
    protected $entity;
    protected $type;
    protected $classController;

    public function __construct($bundle,$entity)
    {
        $this->bundle          = $bundle;
        $this->entity          = $entity;
        $this->entityName      = explode('\\',$entity);
        $this->entityName      = end($this->entityName);
        $this->annotations     = $this->processAnnotation();
        $this->classController = 'Tigreboite\\FunkylabBundle\\Generator\\Controller\\'.$this->type.'Controller';
    }

    public function getController(){}

    public function getViews()
    {
        $files = array();
        $path = dirname(__FILE__)."/Resources/views/".$this->type;
        foreach (glob($path."/*.twig") as $filename) {
            $files[basename($filename)]=$this->fixedCode(file_get_contents($filename));
        }
        return $files;
    }

    private function processAnnotation()
    {
        $annotations = array(
            'variables'             => array(),
            'variables_annotations' => array(),
            'methodes'              => array(),
            'methodes_annotations'  => array(),
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

    public function fixedCode($code)
    {
        $code = str_replace($this->type,$this->entityName,$code);
        $code = str_replace('_'.strtolower($this->type),"_".strtolower($this->entityName),$code);
        $code = str_replace('/admin/'.strtolower($this->type),"/admin/".strtolower($this->entityName),$code);
        $code = str_replace('%entity_name%',$this->entityName,$code);
        $code = str_replace('%class_name%',strtolower($this->entityName),$code);
        $code = str_replace('%bundle_name%',$this->bundle,$code);
        return $code;
    }
}
