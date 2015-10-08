<?php

namespace Tigreboite\FunkylabBundle\Generator;



class GeneratorCrud {

    public function __construct($entity,$bundle,$type) {
        $type = UCFirst(strtolower($type));
        $className = 'Tigreboite\\FunkylabBundle\\Generator\\'.$type.'Formater';
        if(class_exists($className))
        {
            $formater = new $className($bundle,$entity);
            $controller = $formater->getController($type);
        }else{
            throw new \RuntimeException($type." format doesn't exist");
        }
    }


}
?>