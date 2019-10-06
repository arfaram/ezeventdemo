<?php

namespace EzSystems\DemoBundle\Controller;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\SectionService;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LogicalAnd;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\ParentLocationId;
use eZ\Publish\Core\Repository\LocationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    public function __construct(
        ContentService $contentService,
        LocationService $locationService,
        LanguageService $languageService,
        SearchService $searchService,
        SectionService $sectionService
    )
    {
        $this->contentService = $contentService;
        $this->locationService = $locationService;
        $this->languageService = $languageService;
        $this->searchService = $searchService;
        $this->sectionService = $sectionService;
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
//        $loadContentListByContentInfo = $this->contentService->loadContentListByContentInfo([60]);
//
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
//        dump($section);

        return $this->render('EzSystemsDemoBundle:Default:index.html.twig');
    }
}
