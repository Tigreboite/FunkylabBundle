<?php

namespace Tigreboite\FunkylabBundle\Generator;

use Tigreboite\FunkylabBundle\Generator\Formater;
use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpClass;

class FormFormater extends Formater {

    protected $type = "Form";

    protected function formatController()
    {
        $class = parent::getController();

    }
}
