<?php

namespace Tigreboite\FunkylabBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tigreboite\FunkylabBundle\DependencyInjection\Compiler\ConfigCompilerPass;

class TigreboiteFunkylabBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ConfigCompilerPass());
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
