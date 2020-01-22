<?php

namespace EzSystems\DemoBundle\Controller;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LocationService;
use EzSystems\EzPlatformPageFieldType\Registry\LayoutDefinitionRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class RenderLandingPageController
 * @package EzSystems\DemoBundle\Controller
 */
class RenderLandingPageController extends Controller
{
    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    /** @var \EzSystems\DemoBundle\Controller\LayoutDefinitionRegistry */
    private $layoutDefinitionRegistry;

    /** @var \eZ\Publish\API\Repository\LocationService */
    private $locationService;

    /**
     * RenderLandingPageController constructor.
     * @param \eZ\Publish\API\Repository\ContentService $contentService
     * @param \eZ\Publish\API\Repository\LocationService $locationService
     * @param \EzSystems\EzPlatformPageFieldType\Registry\LayoutDefinitionRegistry $layoutDefinitionRegistry
     */
    public function __construct(
        ContentService $contentService,
        LocationService $locationService,
        LayoutDefinitionRegistry $layoutDefinitionRegistry
    )
    {
        $this->contentService = $contentService;
        $this->layoutDefinitionRegistry = $layoutDefinitionRegistry;
        $this->locationService = $locationService;
    }

    /**
     *
     * @Route("/renderpage", name="renderpage_route")
     * @Template("EzSystemsDemoBundle:Default:renderpage.html.twig")
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function renderPageAction()
    {
        $lpContent = $this->contentService->loadContent(476);

        $location = $this->locationService->loadLocation($lpContent->getVersionInfo()->getContentInfo()->mainLocationId);

        /** @var  $pageFieldTypeValue \EzSystems\EzPlatformPageFieldType\FieldType\LandingPage\Value*/
        $pageFieldTypeValue = $lpContent->getFieldValue('page');

        $layout = $pageFieldTypeValue->getPage()->getLayout();

        $layoutDefintion = $this->layoutDefinitionRegistry->getLayoutDefinitionById($layout);

        return
        [
            'lpcontent' => $lpContent,
            'layoutDefinition' => $layoutDefintion,
            'parameters' =>
                [
                    'location' => $location
                ],
            'contentInfo' => $lpContent->getVersionInfo()->getContentInfo(),
            'versionInfo' => $lpContent->getVersionInfo(),
            'field' => $lpContent->getField('page')
        ];

    }

}
