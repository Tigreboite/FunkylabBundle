<?php

namespace Tigreboite\FunkylabBundle\Generator;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;


class GeneratorCrud {
    private $formatter;
    public function __construct($entity,$bundle,$type) {
        $type = UCFirst(strtolower($type));
        $className = 'Tigreboite\\FunkylabBundle\\Generator\\'.$type.'Formater';
        dump($className);
        if(class_exists($className))
        {

            $this->processAnnotation($bundle,$entity,$type);

            $formater = new $className($bundle,$entity);
            $controller = $formater->getController($type);
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
        dump($classAnnotations);


        // fields Annotations
        foreach($reflectionClass->getProperties() as $reflectionProperty)
        {
            $annotations = $annotationReader
              ->getPropertyAnnotations(
                new \ReflectionProperty($entity, $reflectionProperty->getName())
              );
        }

        // get variables
        $methods = $reflectionClass->getProperties();
        foreach($methods as $m)
        {
            $method = new \ReflectionProperty($entity, $m->getName());
        }

        // Methods Annotations
        foreach($reflectionClass->getMethods() as $reflectionMethod)
        {
            $annotations = $annotationReader->getMethodAnnotations(
              new \ReflectionMethod($entity, $reflectionMethod->getName())
            );
        }

        // get Methods
        $methods = $reflectionClass->getMethods();
        foreach($methods as $m)
        {
            $method = new \ReflectionMethod($entity, $m->getName());
        }

    }
}
?>