<?php

namespace EzSystems\DemoBundle\DependencyInjection\Configuration\Parser;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\AbstractParser;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class SiteaccessSettings extends AbstractParser
{
    /**
     *Definition:
     *
     *  ezpublish:
     *      system:
     *          default:
     *              siteaccess_settings:
     *                  header_content_id: 123
     *
     * Usage:
     *      use eZ\Publish\Core\MVC\ConfigResolverInterface;
     *      $this->configResolver->getParameter('siteaccess_settings.header_content_id');
     *
     * @param \Symfony\Component\Config\Definition\Builder\NodeBuilder $nodeBuilder
     */
    public function addSemanticConfig(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder
            ->arrayNode('siteaccess_settings')
                ->info('Global Siteaccess configuration')
                ->children()
                    ->scalarNode('header_content_id')
                        ->defaultValue(null)
                    ->end()
                ->end()
            ->end();
    }
    public function mapConfig(array &$scopeSettings, $currentScope, ContextualizerInterface $contextualizer): void
    {
        if (empty($scopeSettings['siteaccess_settings'])) {
            return;
        }

        $settings = $scopeSettings['siteaccess_settings'];

        if (!empty($settings['header_content_id'])) {
            $contextualizer->setContextualParameter(
                'siteaccess_settings.header_content_id',
                $currentScope,
                $settings['header_content_id']
            );
        }

    }
}
