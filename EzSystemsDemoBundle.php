<?php

namespace EzSystems\DemoBundle;

use EzSystems\DemoBundle\DependencyInjection\Configuration\Parser\SiteaccessSettings;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzSystemsDemoBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        /** @var \eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension $core */
        $core = $container->getExtension('ezpublish');
        $core->addConfigParser(new SiteaccessSettings());
        $core->addDefaultSettings(__DIR__ . '/Resources/config', ['default_settings.yml']);
    }
}
