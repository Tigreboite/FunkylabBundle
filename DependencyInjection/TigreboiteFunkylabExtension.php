<?php

namespace Tigreboite\FunkylabBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\VarDumper\VarDumper;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Cyril Pereira <cyril.pereira@extreme-sensio.com>
 */
class TigreboiteFunkylabExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('events.yml');
        $loader->load('managers.yml');

        $cache = $container->get('funkylab.cache');

        if (isset($config['default_menu'])) {
            $default_menu = [];
            foreach ($config['default_menu'] as $k1 => $v1) {
                $default_menu[$k1] = $v1;
            }
            $cache->save('tigreboite_funkylab.default_menu',$default_menu);
        }

    }

    public function getAlias()
    {
        return 'tigreboite_funkylab';
    }
}
