<?php

namespace Foodmeup\NutritionBundle\QueryHandler\Ingredient;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Query\Ingredient\GetOneIngredientContentQuery;
use Foodmeup\NutritionBundle\Repository\Ingredient\IngredientContentRepository;
use Foodmeup\NutritionBundle\Exception\Ingredient\IngredientContentNotFoundException;

/**
 * Class GetOneIngredientContentQueryHandler.
 */
class GetOneIngredientContentQueryHandler
{
    /**
     * @var IngredientContentRepository
     */
    private $ingredientContentRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor.
     *
     * @param IngredientContentRepository $ingredientContentRepository
     * @param LoggerInterface        $logger
     */
    public function __construct(IngredientContentRepository $ingredientContentRepository, LoggerInterface $logger)
    {
        $this->ingredientContentRepository = $ingredientContentRepository;
        $this->logger = $logger;
    }

    /**
     * Handle.
     *
     * @param GetOneIngredientContentQuery $query
     *
     * @return object
     */
    public function handle(GetOneIngredientContentQuery $query)
    {
        $content = $this->ingredientContentRepository->findOneIngredientContentByIngredientUuidAndUuid(
            $query->getIngredientUuid(), $query->getUuid()
        );

        if ($content === null) {
            $this->logger->warning(
                sprintf('[GetOneIngredientContentQueryHandler::handle] Ingredient content was not found with "ingredientUuid" ("%s") and "uuid" ("%s")',
                    $query->getIngredientUuid(), $query->getUuid())
            );

            throw new IngredientContentNotFoundException(
                sprintf('[INGREDIENT_CONTENT][GET] Ingredient content was not found with "ingredientUuid" ("%s") and "uuid" ("%s")',
                    $query->getIngredientUuid(), $query->getUuid()), null, 404
            );
        }

        return $content;
    }
}
