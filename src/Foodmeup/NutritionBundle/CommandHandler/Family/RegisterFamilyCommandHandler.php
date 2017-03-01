<?php

namespace Foodmeup\NutritionBundle\CommandHandler\Family;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Model\CoreStatus;
use Foodmeup\NutritionBundle\Entity\Family\Family;
use Foodmeup\NutritionBundle\Repository\Family\FamilyRepository;
use Foodmeup\NutritionBundle\Command\Family\RegisterFamilyCommand;

/**
 * Class RegisterFamilyCommandHandler.
 */
class RegisterFamilyCommandHandler
{
    /**
     * Constant used to create in database (identified as "the system").
     */
    const USER_ADMIN = '66666666-6666-6666-6666-666666666666';

    /**
     * @var FamilyRepository
     */
    private $familyRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor.
     *
     * @param FamilyRepository $familyRepository
     * @param LoggerInterface      $logger
     */
    public function __construct(FamilyRepository $familyRepository, LoggerInterface $logger)
    {
        $this->familyRepository = $familyRepository;
        $this->logger = $logger;
    }

    /**
     * Handle.
     *
     * @param RegisterFamilyCommand $command
     *
     * @return object
     */
    public function handle(RegisterFamilyCommand $command)
    {
        $today = new \DateTime();

        $family = new Family(
            $command->getUuid(), CoreStatus::TRIGRAM_PUBLISHED, $today, self::USER_ADMIN, $today,
            self::USER_ADMIN, $command->getOriggpcd()
        );

        $this->familyRepository->save($family);

        $this->logger->info('[FAMILY][REGISTER] Family was saved in database (UUID:'.$command->getUuid().')');
    }
}
