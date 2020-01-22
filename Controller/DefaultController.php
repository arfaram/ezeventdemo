<?php

namespace EzSystems\DemoBundle\Controller;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\SectionService;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LogicalAnd;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\ParentLocationId;
use eZ\Publish\API\Repository\Values\User\Limitation;
use eZ\Publish\Core\Repository\LocationService;
use eZ\Publish\Core\Repository\ObjectStateService;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\Repository\Permission\PermissionResolver;
use eZ\Publish\SPI\Limitation\Target\Builder\VersionBuilder;
use EzSystems\EzPlatformAdminUi\Permission\LookupLimitationsTransformer;
use EzSystems\EzPlatformWorkflow\Value\WorkflowTransition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{

    /**
     * @var ContentService
     */
    private $contentService;
    /**
     * @var LocationService
     */
    private $locationService;
    /**
     * @var LanguageService
     */
    private $languageService;
    /**
     * @var \eZ\Publish\API\Repository\SearchService
     */
    private $searchService;
    /**
     * @var \eZ\Publish\API\Repository\SectionService
     */
    private $sectionService;
    /**
     * @var \eZ\Publish\Core\Repository\ObjectStateService
     */
    private $objectStateService;

    /** @var \eZ\Publish\Core\MVC\ConfigResolverInterface */
    private $configResolver;
    /**
     * @var \eZ\Publish\Core\Repository\Permission\PermissionResolver
     */
    private $permissionResolver;
    /**
     * @var \EzSystems\EzPlatformAdminUi\Permission\LookupLimitationsTransformer
     */
    private $lookupLimitationsTransformer;

    public function __construct(
        ContentService $contentService,
        LocationService $locationService,
        LanguageService $languageService,
        SearchService $searchService,
        SectionService $sectionService,
        ObjectStateService $objectStateService,
        ConfigResolverInterface $configResolver,
        PermissionResolver $permissionResolver,
        LookupLimitationsTransformer $lookupLimitationsTransformer
    )
    {
        $this->contentService = $contentService;
        $this->locationService = $locationService;
        $this->languageService = $languageService;
        $this->searchService = $searchService;
        $this->sectionService = $sectionService;
        $this->objectStateService = $objectStateService;
        $this->configResolver = $configResolver;
        $this->permissionResolver = $permissionResolver;
        $this->lookupLimitationsTransformer = $lookupLimitationsTransformer;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     * @throws \eZ\Publish\Core\Base\Exceptions\InvalidArgumentValue
     */
    public function apiAction()
    {
//        $loadContentInfoList = $this->contentService->loadContentInfoList([60,224]); //new in 2.5
//        $loadContentInfo = $this->contentService->loadContentInfo(60);
//        $loadLocationList = $this->locationService->loadLocationList([62,226]);
//        $loadContentListByContentInfo = $this->contentService->loadContentListByContentInfo($loadContentInfoList);
//        dump($loadContentListByContentInfo);

//        $loadLanguageListByCode = $this->languageService->loadLanguageListByCode(['ger-DE','eng-GB']); //new in 2.5
//        $loadLanguageListById = $this->languageService->loadLanguageListById([2,8]); //new in 2.5
//        $loadLanguages = $this->languageService->loadLanguages();


//---------------------------------------------------------------------------

 //Difference between the searchService->findLocations() and locationService->loadLocationChildren() using given LocationId (Products (LocationId = 54)). Sub-items sorting order ( ContentName(ASC/DESC) or publishedDate(ASC/DESC))

//1. searchService->findLocations() !! Expensive Search

//        $query = new LocationQuery([
//            'filter' => new ParentLocationId(54),
//            'sortClauses' => [new Query\SortClause\DatePublished(Query::SORT_DESC)],
//            //'sortClauses' => [new Query\SortClause\ContentName(Query::SORT_DESC)],
//        ]);
//
//        $searchResult = $this->searchService->findLocations($query);
//        //dump($searchResult);
//        foreach ($searchResult->searchHits as $searchHit ){
//            dump ($searchHit->valueObject->contentInfo->name .' / ' . $searchHit->valueObject->contentInfo->publishedDate->format('Y.m.d H:m.s') );
//        }


//2. locationService->loadLocationChildren()

//        $location = $this->locationService->loadLocation(54);
//        $locationChildren = $this->locationService->loadLocationChildren($location);
//        //dump($locationChildren);
//        foreach ($locationChildren->locations as $childLocation ){
//            dump ($childLocation->contentInfo->name .' / ' . $childLocation->contentInfo->publishedDate->format('Y.m.d H:m.s') );
//        }

//---------------------------------------------------------------------------

        //2.5.3 Changed loadSections method to filter available sections #2707: method to filter the available sections and not throw an exception when the user has no permission to all sections. > 2.5.3 user is redirected to login in front.This change is needed to allow display user a list of section available to him in the administration panel.
//        $section = $this->sectionService->loadSection(1);
//        $sections = $this->sectionService->loadSections();
//        dump($sections);


        //2.5.2 allow editor to translate content in specific language. https://jira.ez.no/browse/EZP-30344
//        $content = $this->contentService->loadContent(523);
//        $languageCode = 'eng-GB';
//        $response = $this->checkEditPermissionAction($content, $languageCode);
//        dump($response);



        return $this->render('EzSystemsDemoBundle:Default:index.html.twig');
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     * @param string|null $languageCode
     * @return array
     * @throws \eZ\Publish\API\Repository\Exceptions\BadStateException
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function checkEditPermissionAction(Content $content, ?string $languageCode)
    {
        $targets = [];
        if (null !== $languageCode) {
            $targets[] = (new VersionBuilder())->translateToAnyLanguageOf([$languageCode])->build();
        }
        $canEdit = $this->permissionResolver->canUser(
            'content',
            'edit',
            $content,
            $targets
        );
        $lookupLimitations = $this->permissionResolver->lookupLimitations(
            'content',
            'edit',
            $content,
            $targets,
            [Limitation::LANGUAGE]
        );

        $editLanguagesLimitationValues = $this->lookupLimitationsTransformer->getFlattenedLimitationsValues($lookupLimitations);

//        $lookupLimitations = $this->permissionResolver->lookupLimitations(
//            'content',
//            'edit',
//            $content,
//            $targets
//        );
//        $editLanguagesLimitationValues = $this->lookupLimitationsTransformer->getGroupedLimitationValues($lookupLimitations, [Limitation::LANGUAGE]);

        $languagesLimitationValues = [
            'canEdit' => $canEdit,
            'editLanguagesLimitationValues' => $canEdit ? $editLanguagesLimitationValues : [],
        ];

        return $languagesLimitationValues;

    }
}
