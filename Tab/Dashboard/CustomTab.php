<?php

declare(strict_types=1);

namespace EzSystems\DemoBundle\Tab\Dashboard;

use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\Core\Pagination\Pagerfanta\ContentSearchAdapter;
use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use EzSystems\EzPlatformAdminUi\Tab\Dashboard\PagerContentToDataMapper;

class CustomTab extends AbstractTab implements OrderedTabInterface
{
    private const PAGINATION_PARAM_NAME = 'page';

    /** @var PagerContentToDataMapper */
    protected $pagerContentToDataMapper;

    /** @var SearchService */
    protected $searchService;
    /**
     * @var RequestStack
     */
    private $requestStack;

    /** @var int */
    private $defaultPaginationLimit;
    /**
     * @var array
     */
    private $contentTypeIdentifier;

    /**
     * CustomTab constructor.
     * @param Environment $twig
     * @param TranslatorInterface $translator
     * @param PagerContentToDataMapper $pagerContentToDataMapper
     * @param SearchService $searchService
     * @param RequestStack $requestStack
     * @param int $defaultPaginationLimit
     * @param array $contentTypeIdentifier
     */
    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        PagerContentToDataMapper $pagerContentToDataMapper,
        SearchService $searchService,
        RequestStack $requestStack,
        int $defaultPaginationLimit,
        array $contentTypeIdentifier
    ) {
        parent::__construct($twig, $translator);

        $this->pagerContentToDataMapper = $pagerContentToDataMapper;
        $this->searchService = $searchService;
        $this->requestStack = $requestStack;
        $this->defaultPaginationLimit = $defaultPaginationLimit;
        $this->contentTypeIdentifier = $contentTypeIdentifier;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return 'custom_tab';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->translator->trans('tab.name.customtab', [], 'dashboard');

    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 200;
    }

    /**
     * @param array $parameters
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function renderView(array $parameters): string
    {
        $currentPage = $this->requestStack->getCurrentRequest()->query->getInt(
            self::PAGINATION_PARAM_NAME, 1
        );

        $query = new Query();
        $query->filter =
            new Criterion\LogicalAnd(array(
                    new Criterion\ContentTypeIdentifier($this->contentTypeIdentifier)
                )
            );

        $pagination = new Pagerfanta(
            new ContentSearchAdapter($query, $this->searchService)
        );
        $pagination->setMaxPerPage($this->defaultPaginationLimit);
        $pagination->setCurrentPage(min(max($currentPage, 1), $pagination->getNbPages()));

        //eZ default table format: customtab.html.twig
        return $this->twig->render('EzSystemsDemoBundle:dashboard/tab:customtab_products.html.twig', [
            'data' => $this->pagerContentToDataMapper->map($pagination),
            'pagination' => $pagination,
            'pager' => $pagination,
            'pager_options' => [
                'pageParameter' => '[' . self::PAGINATION_PARAM_NAME . ']',
            ],
        ]);
    }
}
