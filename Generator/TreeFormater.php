<?php

namespace Tigreboite\FunkylabBundle\Generator;

use Tigreboite\FunkylabBundle\Generator\Formater;

class TreeFormater extends Formater {

    protected $type = "Form";

    protected function formatController()
    {
        $class = parent::getController();
    }
    protected function formatViews()
    {
        $class = parent::getViews();
    }

}
