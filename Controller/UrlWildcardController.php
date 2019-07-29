<?php

namespace EzSystems\DemoBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\API\Repository\Repository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UrlWildcardController extends Controller
{
    /** @var \eZ\Publish\API\Repository\Repository */
    private $repository;

    /**
     * UrlWildcardController constructor.
     * @param \eZ\Publish\API\Repository\Repository $repository
     */
    public function __construct(
        Repository $repository
    )
    {
        $this->repository = $repository;
    }

    /**
     *
     * @Route("/urlwildcard/create", name="url_widcard_create")
     * @Template("EzSystemsDemoBundle:Default:index.html.twig")
     */
    public function createUrlWildcardAction()
    {
        $source = 'travel/*/*';
        $destination = 'places-tastes/{1}/{2}';
        $redirect = true;

        //Be sure you have following policy, if you are not admin and working without sudo: 'content/urltranslator'
        $this->repository->sudo(function () use ($source, $destination, $redirect) {
            $this->repository->getURLWildcardService()->create($source, $destination, $redirect);
        });
    }

    /**
     *
     * @Route("/urlwildcard/load/{id}", name="url_widcard_load")
     * @Template("EzSystemsDemoBundle:Default:index.html.twig")
     */
    public function loadUrlWildcardAction($id)
    {
        $urlWildcardData = $this->repository->getURLWildcardService()->load($id);
    }

    /**
     *
     * @Route("/urlwildcard/load_all", name="url_widcard_load_all")
     * @Template("EzSystemsDemoBundle:Default:index.html.twig")
     */
    public function loadAllUrlWildcardAction()
    {
        $urlWildcardData = $this->repository->getURLWildcardService()->loadAll(0,100);
    }

    /**
     *
     * @Route("/urlwildcard/remove/{id}", name="url_widcard_remove")
     * @Template("EzSystemsDemoBundle:Default:index.html.twig")
     */
    public function removeUrlWildcardAction($id)
    {
        $urlWildcard = $this->repository->getURLWildcardService()->load($id);

        //Be sure you have following policy, if you are not admin and working without sudo: 'content/urltranslator'
        $this->repository->sudo(function () use ($urlWildcard) {
            $this->repository->getURLWildcardService()->remove($urlWildcard);
        });
    }

    /**
     * It just transform your input $url to the destination format. Don't confuse with translation to other languages
     *
     * @Route("/urlwildcard/translate", name="url_widcard_translate")
     * @Template("EzSystemsDemoBundle:Default:index.html.twig")
     */
    public function translateUrlWildcardAction()
    {
        $urlWildcardData = $this->repository->getURLWildcardService()->translate('/travel/places/anchorage-alaska');
    }

}
