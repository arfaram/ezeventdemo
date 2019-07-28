<?php

namespace EzSystems\DemoBundle\Twig;

use ArrayObject;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Tab\LocationView\DetailsTab;
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
    /**
     * @var \EzSystems\EzPlatformAdminUi\Tab\LocationView\DetailsTab
     */
    private $detailsTab;
    /**
     * @var \eZ\Publish\API\Repository\PermissionResolver
     */
    private $permissionResolver;
    /**
     * @var \eZ\Publish\API\Repository\LocationService
     */
    private $locationService;

    public function __construct(
        DatasetFactory $datasetFactory,
        RelationsTab $relationsTab,
        DetailsTab $detailsTab,
        PermissionResolver $permissionResolver,
        LocationService $locationService
    ) {
        $this->datasetFactory = $datasetFactory;
        $this->relationsTab = $relationsTab;
        $this->detailsTab = $detailsTab;
        $this->permissionResolver = $permissionResolver;
        $this->locationService = $locationService;
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
     * @param $location
     * @return array
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function contentCollectionDataSet($content, $location): array
    {
        if (false === $this->permissionResolver->hasAccess('setup', 'administrate')) {
            return [];
        }
        $contextParameters['content'] = $content;
        $contextParameters['location'] = $location;

        $viewParameters = new ArrayObject($this->relationsTab->getTemplateParameters($contextParameters));

        $locationChildren = $this->locationService->loadLocationChildren($location);

        $details = $this->detailsTab->getTemplateParameters($contextParameters);

        $details['locationChildrenTotalCount'] = $locationChildren->totalCount;

        return array_merge($viewParameters->getArrayCopy(), $details);
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'ez_extension';
    }
}
