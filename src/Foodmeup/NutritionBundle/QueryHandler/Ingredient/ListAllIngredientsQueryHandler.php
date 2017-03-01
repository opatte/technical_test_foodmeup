<?php

namespace Foodmeup\NutritionBundle\QueryHandler\Ingredient;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

use Foodmeup\NutritionBundle\Query\Ingredient\ListAllIngredientsQuery;
use Foodmeup\NutritionBundle\Repository\Ingredient\IngredientRepository;

/**
 * Class ListAllIngredientsQueryHandler.
 */
class ListAllIngredientsQueryHandler
{
    /**
     * @var IngredientRepository
     */
    private $ingredientRepository;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor.
     *
     * @param IngredientRepository $ingredientRepository
     * @param Paginator       $paginator
     * @param LoggerInterface $logger
     */
    public function __construct(IngredientRepository $ingredientRepository, Paginator $paginator, LoggerInterface $logger)
    {
        $this->ingredientRepository = $ingredientRepository;
        $this->paginator = $paginator;
        $this->logger = $logger;
    }

    /**
     * Handle.
     *
     * @param ListAllIngredientsQuery $query
     *
     * @return PaginationInterface
     */
    public function handle(ListAllIngredientsQuery $query)
    {
        $query->addFilter('status', $query->getStatus());
        $ingredients = $this->ingredientRepository->getAllIngredientsByFilters($query->getFilters());

        $options = ['sortsAllowed' => $query->getSortsAllowed(), 'sort' => $query->getSort(), 'alias' => 'i'];
        return $this->paginator->paginate($ingredients, $query->getPage(), $query->getLimit(), $options);
    }
}
