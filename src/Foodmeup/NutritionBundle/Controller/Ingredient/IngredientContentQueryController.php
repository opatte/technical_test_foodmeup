<?php

namespace Foodmeup\NutritionBundle\Controller\Ingredient;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Foodmeup\NutritionBundle\Controller\BaseController;
use Foodmeup\NutritionBundle\Query\Ingredient\GetOneIngredientContentQuery;
use Foodmeup\NutritionBundle\Query\Ingredient\ListAllIngredientContentsQuery;
use Foodmeup\NutritionBundle\QueryHandler\Ingredient\GetOneIngredientContentQueryHandler;
use Foodmeup\NutritionBundle\QueryHandler\Ingredient\ListAllIngredientContentsQueryHandler;

/**
 * Class IngredientContentQueryController.
 */
class IngredientContentQueryController extends BaseController
{
    /**
     * @var ListAllIngredientContentsQueryHandler
     */
    private $listAllIngredientContentsQueryHandler;

    /**
     * @var GetOneIngredientContentQueryHandler
     */
    private $getOneIngredientContentQueryHandler;

    /**
     * @var ViewHandler FOSRest view handler
     */
    protected $viewHandler;

    /**
     * @var null|Request
     */
    private $request;

    /**
     * Constructor.
     *
     * @param ListAllIngredientContentsQueryHandler $listAllIngredientContentsQueryHandler
     * @param GetOneIngredientContentQueryHandler   $getOneIngredientContentQueryHandler
     * @param ViewHandler                      $viewHandler
     * @param RequestStack                     $requestStack
     */
    public function __construct(ListAllIngredientContentsQueryHandler $listAllIngredientContentsQueryHandler,
                                GetOneIngredientContentQueryHandler $getOneIngredientContentQueryHandler, ViewHandler $viewHandler,
                                RequestStack $requestStack)
    {
        $this->listAllIngredientContentsQueryHandler = $listAllIngredientContentsQueryHandler;
        $this->getOneIngredientContentQueryHandler = $getOneIngredientContentQueryHandler;

        $this->viewHandler = $viewHandler;
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * Return the ingredient contents list
     * > Using the identifier of a ingredient (INGREDIENT_UUID).
     *
     * @param string $ingredientUuid
     *
     * @return Response
     */
    public function listAllIngredientContentsAction($ingredientUuid)
    {
        $query = new ListAllIngredientContentsQuery($ingredientUuid);

        // Setters (LIMIT, PAGE, SORT & STATUS)
        $query->setLimit($this->request->query->get('limit'));
        $query->setPage($this->request->query->get('page'));
        $query->setSort($this->request->query->get('sort'));
        $query->setStatus($this->request->query->get('status'));

        // Others filters
        $query->addFilters($this->request->query->all());

        $contents = $this->listAllIngredientContentsQueryHandler->handle($query);
        $paginatedCollection = $this->getPaginatedRepresentation(
            $contents, 'contents', 'contents', 'No ingredient contents founded'
        );

        return $this->viewHandler->handle(View::create($paginatedCollection, 200));
    }

    /**
     * Return the one content of ingredient
     * > Using its identifier (UUID) and the identifier of a ingredient (INGREDIENT_UUID).
     *
     * @param string $ingredientUuid
     * @param string $contentUuid
     *
     * @return Response
     */
    public function getOneIngredientContentAction($ingredientUuid, $contentUuid)
    {
        $getOneIngredientContentQuery = new GetOneIngredientContentQuery($ingredientUuid, $contentUuid);
        $ingredientContent = $this->getOneIngredientContentQueryHandler->handle($getOneIngredientContentQuery);

        return $this->viewHandler->handle(View::create($ingredientContent, 200));
    }
}
