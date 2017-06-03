<?php

namespace Tigreboite\FunkylabBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TigreboiteFunkylabBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
