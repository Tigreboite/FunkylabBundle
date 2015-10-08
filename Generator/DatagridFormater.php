<?php

namespace Tigreboite\FunkylabBundle\Generator;

use Tigreboite\FunkylabBundle\Generator\Formater;

class DatagridFormater extends Formater {

    protected $type = "Datagrid";

    private function formatController()
    {
        $class = parent::getController();

    }
}
