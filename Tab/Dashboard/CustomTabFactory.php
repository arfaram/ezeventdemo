<?php

declare(strict_types=1);

namespace EzSystems\DemoBundle\Tab\Dashboard;

use eZ\Publish\API\Repository\SearchService;

use EzSystems\EzPlatformAdminUi\Tab\Dashboard\PagerContentToDataMapper;
use EzSystems\EzPlatformUser\UserSetting\UserSettingService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class CustomTabFactory
 * @package EzSystems\DemoBundle\Tab\Dashboard
 */
class CustomTabFactory
{
    /**
     * @var UserSettingService
     */
    private $userSettingService;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var PagerContentToDataMapper
     */
    private $pagerContentToDataMapper;
    /**
     * @var SearchService
     */
    private $searchService;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var string
     */
    private $userSettingValueIdentifier;
    /**
     * @var array
     */
    private $contentTypeIdentifier;

    /**
     * customTabFactory constructor.
     * @param UserSettingService $userSettingService
     * @param Environment $twig
     * @param TranslatorInterface $translator
     * @param PagerContentToDataMapper $pagerContentToDataMapper
     * @param SearchService $searchService
     * @param RequestStack $requestStack
     */
    public function __construct(
        UserSettingService $userSettingService,
        Environment $twig,
        TranslatorInterface $translator,
        PagerContentToDataMapper $pagerContentToDataMapper,
        SearchService $searchService,
        RequestStack $requestStack,
        string $userSettingCustomTabValueIdentifier,
        array $contentTypeIdentifier
    ) {

        $this->userSettingService = $userSettingService;
        $this->twig = $twig;
        $this->translator = $translator;
        $this->pagerContentToDataMapper = $pagerContentToDataMapper;
        $this->searchService = $searchService;
        $this->requestStack = $requestStack;
        $this->userSettingCustomTabValueIdentifier = $userSettingCustomTabValueIdentifier;
        $this->contentTypeIdentifier = $contentTypeIdentifier;
    }

    /**
     * @return string
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function setTabValue()
    {
        //pagination value from the user preferences
        $storageValue = $this->userSettingService->getUserSetting($this->userSettingCustomTabValueIdentifier)->value;

        return new CustomTab(
            $this->twig,
            $this->translator,
            $this->pagerContentToDataMapper,
            $this->searchService,
            $this->requestStack,
            (int) $storageValue,
            $this->contentTypeIdentifier
        );

    }

}