<?php

namespace Tigreboite\FunkylabBundle\Generator;

use Tigreboite\FunkylabBundle\Generator\Formater;

class TreeFormater extends Formater {

    protected $type = "Form";

    public function getController()
    {
        $class = PhpClass::fromReflection(new \ReflectionClass($this->classController));

        $class->setQualifiedName($this->bundle.'\\Controller\\'.$this->entityName.'Controller extends Controller');
        $class->addUseStatement($this->entity);
        $class->addUseStatement($this->entity."Type");

        $generator = new CodeGenerator();

        $code = $generator->generate($class);
        $code = $this->fixedCode($code);
        return $code;
    }

}
