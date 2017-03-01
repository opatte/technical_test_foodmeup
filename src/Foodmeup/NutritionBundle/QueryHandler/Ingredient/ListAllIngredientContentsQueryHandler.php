<?php

namespace Foodmeup\NutritionBundle\QueryHandler\Ingredient;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

use Foodmeup\NutritionBundle\Query\Ingredient\ListAllIngredientContentsQuery;
use Foodmeup\NutritionBundle\Repository\Ingredient\IngredientContentRepository;

/**
 * Class ListAllIngredientContentsQueryHandler.
 */
class ListAllIngredientContentsQueryHandler
{
    /**
     * @var IngredientContentRepository
     */
    private $ingredientContentRepository;

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
     * @param IngredientContentRepository $ingredientContentRepository
     * @param Paginator              $paginator
     * @param LoggerInterface        $logger
     */
    public function __construct(IngredientContentRepository $ingredientContentRepository, Paginator $paginator,
                                LoggerInterface $logger)
    {
        $this->ingredientContentRepository = $ingredientContentRepository;
        $this->paginator = $paginator;
        $this->logger = $logger;
    }

    /**
     * Handle.
     *
     * @param ListAllIngredientContentsQuery $query
     *
     * @return PaginationInterface
     */
    public function handle(ListAllIngredientContentsQuery $query)
    {
        $query->addFilter('status', $query->getStatus());
        $contents = $this->ingredientContentRepository->getAllIngredientContentsByIngredientUuidAndFilters(
            $query->getIngredientUuid(), $query->getFilters()
        );

        $options = ['sortsAllowed' => $query->getSortsAllowed(), 'sort' => $query->getSort(), 'alias' => 'ic'];
        return $this->paginator->paginate($contents, $query->getPage(), $query->getLimit(), $options);
    }
}
