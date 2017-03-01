<?php

namespace Foodmeup\NutritionBundle\CommandHandler\Family;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Slugger\Slugger;
use Foodmeup\NutritionBundle\Model\CoreStatus;
use Foodmeup\NutritionBundle\Entity\Family\FamilyContent;
use Foodmeup\NutritionBundle\Repository\Family\FamilyContentRepository;
use Foodmeup\NutritionBundle\Command\Family\RegisterFamilyContentCommand;

/**
 * Class RegisterFamilyContentCommandHandler.
 */
class RegisterFamilyContentCommandHandler
{
    /**
     * Constant used to create in database (identified as "the system").
     */
    const USER_ADMIN = '66666666-6666-6666-6666-666666666666';

    /**
     * @var FamilyContentRepository
     */
    private $familyContentRepository;

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
     * @param FamilyContentRepository $familyContentRepository
     * @param Slugger                     $slugger
     * @param LoggerInterface             $logger
     */
    public function __construct(FamilyContentRepository $familyContentRepository, Slugger $slugger,
                                LoggerInterface $logger)
    {
        $this->familyContentRepository = $familyContentRepository;
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    /**
     * Handle.
     *
     * @param RegisterFamilyContentCommand $command
     *
     * @return object
     */
    public function handle(RegisterFamilyContentCommand $command)
    {
        $today = new \DateTime();

        $familyContent = new FamilyContent(
            $command->getUuid(), CoreStatus::TRIGRAM_PUBLISHED, $today, self::USER_ADMIN, $today,
            self::USER_ADMIN, $command->getName(), $this->slugger->slugify($command->getName()),
            $command->getLang(), $command->getFamily()
        );

        $this->familyContentRepository->save($familyContent);

        $this->logger->info('[FAMILY][REGISTER] Family content was saved in database (UUID:'.$command->getUuid().')');
    }
}
