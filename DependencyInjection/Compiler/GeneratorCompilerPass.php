<?php

namespace Tigreboite\FunkylabBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GeneratorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->chain($container, 'formaters');
        $this->chain($container, 'fields');
    }

    /**
     * Process Formats generator
     * @param ContainerBuilder $container
     */
    private function chain(ContainerBuilder $container, $chainName)
    {
        if (!$container->has('tigreboitefunkylab.'.$chainName.'.chain')) {
            return;
        }

        $definition = $container->findDefinition('tigreboitefunkylab.'.$chainName.'.chain');

        $taggedServices = $container->findTaggedServiceIds('funkylab.'.$chainName);

        $ids = [];
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('add', array(new Reference($id)));
            $ids[]=$id;
        }

        $collectorDefinition = $container->findDefinition('funkylab.service');
        $collectorDefinition->addMethodCall('set', array($chainName, $ids));
    }
}
