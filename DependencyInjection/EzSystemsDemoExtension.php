<?php

namespace EzSystems\DemoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EzSystemsDemoExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('dashboard.yml');
        $loader->load('form.yml');
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containe
     */
    public function prepend(ContainerBuilder $container)
    {
        $configs = array(
            'ezplatform.yml' => 'ezpublish',
            'custom_tags.yml' => 'ezrichtext',
            'config.yml' => 'twig'
        );

        foreach ($configs as $fileName => $extensionName) {
            $configFile = __DIR__ . '/../Resources/config/' . $fileName;
            $config = Yaml::parseFile($configFile);
            $container->prependExtensionConfig($extensionName, $config);
        }
    }
}
