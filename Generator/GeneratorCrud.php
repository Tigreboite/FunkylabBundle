<?php

namespace Tigreboite\FunkylabBundle\Generator;

abstract class Generator {
    protected $formatter;
    protected function __construct($entity,$bundle,$type) {
        $className = 'Tigreboite\\FunkylabBundle\\Generator\\'.UCFirst($type);
        if(class_exists($className))
        {
            $formater = new $className();
            dump($formater);
        }else{
            throw new \RuntimeException($type." format doesn't exist");
        }

    }
}
?>