<?php

namespace Foodmeup\NutritionBundle\Controller\Ingredient;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

use Foodmeup\NutritionBundle\Controller\BaseController;
use Foodmeup\NutritionBundle\Query\Ingredient\GetOneIngredientQuery;
use Foodmeup\NutritionBundle\Query\Ingredient\ListAllIngredientsQuery;
use Foodmeup\NutritionBundle\QueryHandler\Ingredient\GetOneIngredientQueryHandler;
use Foodmeup\NutritionBundle\QueryHandler\Ingredient\ListAllIngredientsQueryHandler;

/**
 * Class IngredientQueryController.
 */
class IngredientQueryController extends BaseController
{
    /**
     * @var ListAllIngredientsQueryHandler
     */
    private $listAllIngredientsQueryHandler;

    /**
     * @var GetOneIngredientQueryHandler
     */
    private $getOneIngredientQueryHandler;

    /**
     * @var ViewHandler View handler
     */
    private $viewHandler;

    /**
     * @var null|Request
     */
    private $request;

    /**
     * Constructor.
     *
     * @param ListAllIngredientsQueryHandler $listAllIngredientsQueryHandler
     * @param GetOneIngredientQueryHandler   $getOneIngredientQueryHandler
     * @param ViewHandler               $viewHandler
     * @param RequestStack              $requestStack
     */
    public function __construct(ListAllIngredientsQueryHandler $listAllIngredientsQueryHandler,
                                GetOneIngredientQueryHandler $getOneIngredientQueryHandler, ViewHandler $viewHandler,
                                RequestStack $requestStack)
    {
        $this->listAllIngredientsQueryHandler = $listAllIngredientsQueryHandler;
        $this->getOneIngredientQueryHandler = $getOneIngredientQueryHandler;

        $this->viewHandler = $viewHandler;
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * Return the ingredients list.
     *
     * @return Response
     */
    public function listAllIngredientsAction()
    {
        $query = new ListAllIngredientsQuery();

        // Setters (LIMIT, PAGE, SORT & STATUS)
        $query->setLimit($this->request->query->get('limit'));
        $query->setPage($this->request->query->get('page'));
        $query->setSort($this->request->query->get('sort'));
        $query->setStatus($this->request->query->get('status'));

        $query->setFields($this->request->query->get('fields'));
        $query->setEmbeddedsFromGetRequest($this->request->query->get('embedded'));

        // Others filters
        $query->addFilters($this->request->query->all());

        $ingredients = $this->listAllIngredientsQueryHandler->handle($query);
        $data = $this->getPaginatedRepresentation($ingredients, 'ingredients', 'ingredients', 'No ingredients founded');

        return $this->viewHandler->handle(View::create($data, 200));
    }

    /**
     * Return the one ingredient using its identifier (UUID).
     *
     * @param string $uuid
     *
     * @return Response
     */
    public function getOneIngredientAction($uuid)
    {
        $query = new GetOneIngredientQuery($uuid);
        $ingredient = $this->getOneIngredientQueryHandler->handle($query);

        return $this->viewHandler->handle(View::create($ingredient, 200));
    }
}
