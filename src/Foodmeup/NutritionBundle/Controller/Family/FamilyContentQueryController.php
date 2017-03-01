<?php

namespace Foodmeup\NutritionBundle\Controller\Family;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

use Foodmeup\NutritionBundle\Controller\BaseController;
use Foodmeup\NutritionBundle\Query\Family\GetOneFamilyContentQuery;
use Foodmeup\NutritionBundle\Query\Family\ListAllFamilyContentsQuery;
use Foodmeup\NutritionBundle\QueryHandler\Family\GetOneFamilyContentQueryHandler;
use Foodmeup\NutritionBundle\QueryHandler\Family\ListAllFamilyContentsQueryHandler;

/**
 * Class FamilyContentQueryController.
 */
class FamilyContentQueryController extends BaseController
{
    /**
     * @var ListAllFamilyContentsQueryHandler
     */
    private $listAllFamilyContentsQueryHandler;

    /**
     * @var GetOneFamilyContentQueryHandler
     */
    private $getOneFamilyContentQueryHandler;

    /**
     * @var ViewHandler
     */
    private $viewHandler;

    /**
     * @var null|Request
     */
    private $request;

    /**
     * Constructor.
     *
     * @param ListAllFamilyContentsQueryHandler   $listAllFamilyContentsQueryHandler
     * @param GetOneFamilyContentQueryHandler $getOneFamilyContentQueryHandler
     * @param ViewHandler                         $viewHandler
     * @param RequestStack                        $requestStack
     */
    public function __construct(ListAllFamilyContentsQueryHandler $listAllFamilyContentsQueryHandler,
                                GetOneFamilyContentQueryHandler $getOneFamilyContentQueryHandler,
                                ViewHandler $viewHandler, RequestStack $requestStack)
    {
        $this->listAllFamilyContentsQueryHandler = $listAllFamilyContentsQueryHandler;
        $this->getOneFamilyContentQueryHandler = $getOneFamilyContentQueryHandler;

        $this->viewHandler = $viewHandler;
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * Return the family contents list
     * > Using the identifier of a family (FAMILY_UUID).
     *
     * @param string $familyUuid
     *
     * @return Response
     */
    public function listAllFamilyContentsAction($familyUuid)
    {
        $query = new ListAllFamilyContentsQuery($familyUuid);

        // Setters (LIMIT, PAGE, SORT & STATUS)
        $query->setLimit($this->request->query->get('limit'));
        $query->setPage($this->request->query->get('page'));
        $query->setSort($this->request->query->get('sort'));
        $query->setStatus($this->request->query->get('status'));

        $query->setFields($this->request->query->get('fields'));
        $query->setEmbeddedsFromGetRequest($this->request->query->get('embedded'));

        // Others filters
        $query->addFilters($this->request->query->all());

        $contents = $this->listAllFamilyContentsQueryHandler->handle($query);
        $data = $this->getPaginatedRepresentation($contents, 'contents', 'contents', 'No family contents founded');

        return $this->viewHandler->handle(View::create($data, 200));
    }

    /**
     * Return the one content of family
     * > Using its identifier (CONTENT_UUID) and the identifier of a family (FAMILY_UUID).
     *
     * @param string $familyUuid
     * @param string $contentUuid
     *
     * @return Response
     */
    public function getOneFamilyContentAction($familyUuid, $contentUuid)
    {
        $query = new GetOneFamilyContentQuery($familyUuid, $contentUuid);
        $familyContent = $this->getOneFamilyContentQueryHandler->handle($query);

        return $this->viewHandler->handle(View::create($familyContent, 200));
    }
}
