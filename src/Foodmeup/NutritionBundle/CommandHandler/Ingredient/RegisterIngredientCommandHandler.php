<?php

namespace Foodmeup\NutritionBundle\CommandHandler\Ingredient;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Model\CoreStatus;
use Foodmeup\NutritionBundle\Entity\Ingredient\Ingredient;
use Foodmeup\NutritionBundle\Repository\Ingredient\IngredientRepository;
use Foodmeup\NutritionBundle\Command\Ingredient\RegisterIngredientCommand;

/**
 * Class RegisterIngredientCommandHandler.
 */
class RegisterIngredientCommandHandler
{
    /**
     * Constant used to create in database (identified as "the system").
     */
    const USER_ADMIN = '66666666-6666-6666-6666-666666666666';

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
     * @param RegisterIngredientCommand $command
     *
     * @return object
     */
    public function handle(RegisterIngredientCommand $command)
    {
        $today = new \DateTime();

        $ingredient = new Ingredient(
            $command->getUuid(), CoreStatus::TRIGRAM_PUBLISHED, $today, self::USER_ADMIN, $today,
            self::USER_ADMIN, $command->getOrigfdcd(), $command->getFamily()
        );

        $this->ingredientRepository->save($ingredient);

        $this->logger->info('[INGREDIENT][REGISTER] Ingredient was saved in database (UUID:'.$command->getUuid().')');
    }
}
