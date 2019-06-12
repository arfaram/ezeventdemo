<?php

namespace EzSystems\DemoBundle\Controller;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LanguageService;
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

    public function __construct(
        ContentService $contentService,
        LocationService $locationService,
        LanguageService $languageService
    )
    {
        $this->contentService = $contentService;
        $this->locationService = $locationService;
        $this->languageService = $languageService;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
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


        return $this->render('EzSystemsDemoBundle:Default:index.html.twig');
    }
}
