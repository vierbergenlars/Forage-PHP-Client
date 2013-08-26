<?php

namespace vierbergenlars\Forage\Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class vierbergenlarsForageExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('vierbergenlars.forage.transport.location', $config['transport']['location']);
        switch($config['transport']['type']) {
            case 'http':
                $container->setParameter('vierbergenlars.forage.transport.class', '%vierbergenlars.forage.transport.http.class%');
                break;
            default:
                throw new \InvalidArgumentException('transport.type must be http');
        }
        if(isset($config['dm'])) {
            $container->setParameter('vierbergenlars.forage.dm.hydrationsettings.class', $config['dm']['hydration']);
        }
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
