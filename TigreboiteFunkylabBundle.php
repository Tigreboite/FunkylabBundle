<?php

namespace Tigreboite\FunkylabBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tigreboite\FunkylabBundle\DependencyInjection\Compiler\ConfigCompilerPass;
use Tigreboite\FunkylabBundle\DependencyInjection\Compiler\GeneratorCompilerPass;

class TigreboiteFunkylabBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ConfigCompilerPass());
        $container->addCompilerPass(new GeneratorCompilerPass());
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
