<?php

namespace EzSystems\DemoBundle\Twig;

use ArrayObject;
use eZ\Publish\API\Repository\ContentTypeService;
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
    /**
     * @var \eZ\Publish\API\Repository\ContentTypeService
     */
    private $contentTypeService;

    public function __construct(
        DatasetFactory $datasetFactory,
        RelationsTab $relationsTab,
        DetailsTab $detailsTab,
        PermissionResolver $permissionResolver,
        LocationService $locationService,
        ContentTypeService $contentTypeService
    ) {
        $this->datasetFactory = $datasetFactory;
        $this->relationsTab = $relationsTab;
        $this->detailsTab = $detailsTab;
        $this->permissionResolver = $permissionResolver;
        $this->locationService = $locationService;
        $this->contentTypeService = $contentTypeService;
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
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function contentCollectionDataSet($content, $location): array
    {
        if (false === $this->permissionResolver->hasAccess('setup', 'administrate')) {
            return [];
        }
        $contextParameters['content'] = $content;
        $contextParameters['location'] = $location;

        $locationChildren = $this->locationService->loadLocationChildren($location);

        $contentDetails = $this->detailsTab->getTemplateParameters($contextParameters);

        $contentDetails['locationChildrenTotalCount'] = $locationChildren->totalCount;

        return array_merge($this->getRelationInformation($contextParameters), $contentDetails);
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'ez_extension';
    }

    /**
     * @param array $contextParameters
     * @return array
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function getRelationInformation(array $contextParameters = []): array
    {
        /** @var Content $content */
        $content = $contextParameters['content'];
        $relations =
            $this->datasetFactory
                ->relationList()
                ->load($content)
                ->getRelations();

        foreach ($relations as $relation) {
            $contentTypeIds[] = $relation->getDestinationContentInfo()->contentTypeId;
        }

        $contentRelations['relations'] = $relations;

        if (true === $this->permissionResolver->hasAccess('content', 'reverserelatedlist')) {
            $reverseRelations = $this->datasetFactory
                ->reverseRelationList()
                ->load($content)
                ->getReverseRelations();

            foreach ($reverseRelations as $relation) {
                $contentTypeIds[] = $relation->getSourceContentInfo()->contentTypeId;
            }

            $contentRelations['reverse_relations'] = $reverseRelations;
        }

        if (!empty($contentTypeIds)) {
            $contentRelations['contentTypes'] = $this->contentTypeService->loadContentTypeList(array_unique($contentTypeIds));
        } else {
            $contentRelations['contentTypes'] = [];
        }
        return array_replace($contextParameters, $contentRelations);
    }
}
