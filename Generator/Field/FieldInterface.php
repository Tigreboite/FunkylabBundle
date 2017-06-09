<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

interface FieldInterface
{
    public function getVarname();

    public function getName();

    public function getOptions();

    public function getHTML();

    public function getJS();

    public function getUseType();

    public function getBuilder();

    public function getType();
}
