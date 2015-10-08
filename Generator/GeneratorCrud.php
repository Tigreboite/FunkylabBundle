<?php

namespace Tigreboite\FunkylabBundle\Generator;



class GeneratorCrud {

    public function __construct($entity,$bundle,$type) {
        $type = UCFirst(strtolower($type));
        $this->entityName   = explode('\\',$entity);
        $this->entityName   = end($this->entityName);
        $path = explode('vendor',dirname(__FILE__));
        $path = $path[0]."src/".$bundle."/";

        $className = 'Tigreboite\\FunkylabBundle\\Generator\\'.$type.'Formater';
        if(class_exists($className))
        {
            $formater = new $className($bundle,$entity);
            $code_controller = $formater->getController($type);


            file_put_contents($path."Controller/".$this->entityName."Controller.php","<?php\n".$code_controller);

        }else{
            throw new \RuntimeException($type." format doesn't exist");
        }
    }


}
?>