<?php

namespace Foodmeup\NutritionBundle\Controller\Family;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

use Foodmeup\NutritionBundle\Controller\BaseController;
use Foodmeup\NutritionBundle\Query\Family\GetOneFamilyQuery;
use Foodmeup\NutritionBundle\Query\Family\ListAllFamiliesQuery;
use Foodmeup\NutritionBundle\QueryHandler\Family\GetOneFamilyQueryHandler;
use Foodmeup\NutritionBundle\QueryHandler\Family\ListAllFamiliesQueryHandler;

/**
 * Class FamilyQueryController.
 */
class FamilyQueryController extends BaseController
{
    /**
     * @var ListAllFamiliesQueryHandler
     */
    private $listAllFamiliesQueryHandler;

    /**
     * @var GetOneFamilyQueryHandler
     */
    private $getOneFamilyQueryHandler;

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
     * @param ListAllFamiliesQueryHandler   $listAllFamiliesQueryHandler
     * @param GetOneFamilyQueryHandler $getOneFamilyQueryHandler
     * @param ViewHandler                  $viewHandler
     * @param RequestStack                 $requestStack
     */
    public function __construct(ListAllFamiliesQueryHandler $listAllFamiliesQueryHandler,
                                GetOneFamilyQueryHandler $getOneFamilyQueryHandler,
                                ViewHandler $viewHandler, RequestStack $requestStack)
    {
        $this->listAllFamiliesQueryHandler = $listAllFamiliesQueryHandler;
        $this->getOneFamilyQueryHandler = $getOneFamilyQueryHandler;

        $this->viewHandler = $viewHandler;
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * Return the families list.
     *
     * @return Response
     */
    public function listAllFamiliesAction()
    {
        $query = new ListAllFamiliesQuery();

        // Setters (LIMIT, PAGE, SORT & STATUS)
        $query->setLimit($this->request->query->get('limit'));
        $query->setPage($this->request->query->get('page'));
        $query->setSort($this->request->query->get('sort', 'origgpcd ASC'));
        $query->setStatus($this->request->query->get('status'));

        $query->setFields($this->request->query->get('fields'));
        $query->setEmbeddedsFromGetRequest($this->request->query->get('embedded'));

        // Others filters
        $query->addFilters($this->request->query->all());

        $families = $this->listAllFamiliesQueryHandler->handle($query);
        $data = $this->getPaginatedRepresentation($families, 'families', 'families', 'No families founded');

        return $this->viewHandler->handle(View::create($data, 200));
    }

    /**
     * Return the one family using its identifier (UUID).
     *
     * @param string $uuid
     *
     * @return Response
     */
    public function getOneFamilyAction($uuid)
    {
        $query = new GetOneFamilyQuery($uuid);
        $family = $this->getOneFamilyQueryHandler->handle($query);

        return $this->viewHandler->handle(View::create($family, 200));
    }
}
