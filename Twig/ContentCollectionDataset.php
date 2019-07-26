<?php

namespace EzSystems\DemoBundle\Twig;

use EzSystems\EzPlatformAdminUi\Tab\LocationView\RelationsTab;
use EzSystems\EzPlatformAdminUi\UI\Dataset\DatasetFactory;
use Twig\TwigFunction;

/**
 * Class ContentRelationsDataset
 * @package AppBundle\Twig
 */
class ContentCollectionDataset extends \Twig_Extension
{
    /**
     * @var \EzSystems\EzPlatformAdminUi\UI\Dataset\DatasetFactory
     */
    private $datasetFactory;
    /**
     * @var \EzSystems\EzPlatformAdminUi\Tab\LocationView\RelationsTab
     */
    private $relationsTab;

    public function __construct(
        DatasetFactory $datasetFactory,
        RelationsTab $relationsTab
    ) {
        $this->datasetFactory = $datasetFactory;
        $this->relationsTab = $relationsTab;
    }

    /**
     * @return array|\Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('get_content_collection_data_set', array($this, 'contentCollectionDataSet')),
        );
    }

    /**
     * @param $content
     * @return array
     */
    public function contentCollectionDataSet($content): array
    {
        $contextParameters['content'] = $content;
        $viewParameters = $this->relationsTab->getTemplateParameters($contextParameters);

        $viewParameters['translations'] =  $this->contentTranslations($content->getVersionInfo());

        return $viewParameters;
    }

    public function contentTranslations($contentVersionInfo)
    {
        $translationsDataset = $this->datasetFactory->translations();
        $translationsDataset->load($contentVersionInfo);

        return $translationsDataset->getTranslations();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ez_extension';
    }
}
