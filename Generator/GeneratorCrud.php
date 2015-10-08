<?php

namespace Tigreboite\FunkylabBundle\Generator;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;


class GeneratorCrud {

    public function __construct($entity,$bundle,$type) {
        $type = UCFirst(strtolower($type));
        $className = 'Tigreboite\\FunkylabBundle\\Generator\\'.$type.'Formater';
        if(class_exists($className))
        {
            $this->processAnnotation($bundle,$entity,$type);
            $formater = new $className($bundle,$entity);
            $controller = $formater->getController($type);
            dump($controller);
        }else{
            throw new \RuntimeException($type." format doesn't exist");
        }
    }

    private function processAnnotation($bundle,$entity,$type)
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($entity);

        // Class Annotations
        $classAnnotations = $annotationReader->getClassAnnotations($reflectionClass);

        // fields Annotations
        foreach($reflectionClass->getProperties() as $reflectionProperty)
        {
            $variable = new \ReflectionProperty($entity, $reflectionProperty->getName());
            $variable_annotations = $annotationReader->getPropertyAnnotations($variable);
        }

        // Methods Annotations
        foreach($reflectionClass->getMethods() as $reflectionMethod)
        {
            $method = new \ReflectionMethod($entity, $reflectionMethod->getName());
            $method_annotations = $annotationReader->getMethodAnnotations($method);
        }

    }
}
?>