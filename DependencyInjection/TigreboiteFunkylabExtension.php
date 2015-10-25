<?php

namespace Tigreboite\FunkylabBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Cyril Pereira <cyril.pereira@extreme-sensio.com>
 */
class TigreboiteFunkylabExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        foreach($config as $k1=>$v1)
        {
            if(is_array($v1))
            {
                foreach($v1 as $k2=>$v2)
                {
                    $container->setParameter('tigreboite_funkylab.'.$k1.'.'.$k2, $v2);
                }
            }else{
                $container->setParameter('tigreboite_funkylab.'.$k1, $v1);
            }


        }

    }
}
