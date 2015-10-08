<?php

namespace Tigreboite\FunkylabBundle\Generator;

use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpClass;

use Tigreboite\FunkylabBundle\Generator\Formater;

class DatagridFormater extends Formater {

    protected $type = "Datagrid";

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
