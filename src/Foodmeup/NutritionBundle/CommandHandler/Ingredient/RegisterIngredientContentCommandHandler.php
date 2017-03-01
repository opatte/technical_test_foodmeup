<?php

namespace Foodmeup\NutritionBundle\CommandHandler\Ingredient;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Slugger\Slugger;
use Foodmeup\NutritionBundle\Model\CoreStatus;
use Foodmeup\NutritionBundle\Entity\Ingredient\IngredientContent;
use Foodmeup\NutritionBundle\Repository\Ingredient\IngredientContentRepository;
use Foodmeup\NutritionBundle\Command\Ingredient\RegisterIngredientContentCommand;

/**
 * Class RegisterIngredientContentCommandHandler.
 */
class RegisterIngredientContentCommandHandler
{
    /**
     * Constant used to create in database (identified as "the system").
     */
    const USER_ADMIN = '66666666-6666-6666-6666-666666666666';

    /**
     * @var IngredientContentRepository
     */
    private $ingredientContentRepository;

    /**
     * @var Slugger
     */
    private $slugger;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor.
     *
     * @param IngredientContentRepository $ingredientContentRepository
     * @param Slugger                $slugger
     * @param LoggerInterface        $logger
     */
    public function __construct(IngredientContentRepository $ingredientContentRepository, Slugger $slugger,
                                LoggerInterface $logger)
    {
        $this->ingredientContentRepository = $ingredientContentRepository;
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    /**
     * Handle.
     *
     * @param RegisterIngredientContentCommand $command
     *
     * @return object
     */
    public function handle(RegisterIngredientContentCommand $command)
    {
        $today = new \DateTime();

        $ingredientContent = new IngredientContent(
            $command->getUuid(), CoreStatus::TRIGRAM_PUBLISHED, $today, self::USER_ADMIN, $today,
            self::USER_ADMIN, $command->getName(), $this->slugger->slugify($command->getName()),
            $command->getLang(), $command->getIngredient()
        );

        $this->ingredientContentRepository->save($ingredientContent);

        $this->logger->info(
            '[INGREDIENT][REGISTER] Ingredient content was saved in database (UUID:'.$command->getUuid().')'
        );
    }
}
