<?php

namespace Foodmeup\NutritionBundle\QueryHandler\Ingredient;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Query\Ingredient\GetOneIngredientQuery;
use Foodmeup\NutritionBundle\Repository\Ingredient\IngredientRepository;
use Foodmeup\NutritionBundle\Exception\Ingredient\IngredientNotFoundException;

/**
 * Class GetOneIngredientQueryHandler.
 */
class GetOneIngredientQueryHandler
{
    /**
     * @var IngredientRepository
     */
    private $ingredientRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor.
     *
     * @param IngredientRepository $ingredientRepository
     * @param LoggerInterface $logger
     */
    public function __construct(IngredientRepository $ingredientRepository, LoggerInterface $logger)
    {
        $this->ingredientRepository = $ingredientRepository;
        $this->logger = $logger;
    }

    /**
     * Handle.
     *
     * @param GetOneIngredientQuery $query
     *
     * @return object
     */
    public function handle(GetOneIngredientQuery $query)
    {
        $ingredient = $this->ingredientRepository->findOneBy(['uuid' => $query->getUuid()]);

        if ($ingredient === null) {
            $this->logger->warning(
                '[GetOneIngredientQueryHandler::handle] Ingredient was not found with uuid:'.$query->getUuid()
            );

            throw new IngredientNotFoundException(
                '[INGREDIENT][GET] Ingredient was not found with UUID:'.$query->getUuid(), null, 404
            );
        }

        return $ingredient;
    }
}
