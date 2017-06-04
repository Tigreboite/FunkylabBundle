<?php

namespace Tigreboite\FunkylabBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter("funkylab");
        $definitionTwig = $container->findDefinition('twig');
        $definitionTwig->addMethodCall('addGlobal', array("funkylab",$config));

        $definitionCollector = $container->findDefinition('funkylab.service');
        $definitionCollector->addMethodCall('set', array("config",$config));
    }
}
