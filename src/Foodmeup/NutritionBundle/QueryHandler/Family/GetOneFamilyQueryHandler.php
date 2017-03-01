<?php

namespace Foodmeup\NutritionBundle\QueryHandler\Family;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Query\Family\GetOneFamilyQuery;
use Foodmeup\NutritionBundle\Repository\Family\FamilyRepository;
use Foodmeup\NutritionBundle\Exception\Family\FamilyNotFoundException;

/**
 * Class GetOneFamilyQueryHandler.
 */
class GetOneFamilyQueryHandler
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FamilyRepository
     */
    private $familyRepository;

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
     * @param GetOneFamilyQuery $query
     *
     * @return null|object
     */
    public function handle(GetOneFamilyQuery $query)
    {
        $family = $this->familyRepository->findOneBy(['uuid' => $query->getUuid()]);

        if ($family === null) {
            $this->logger->warning(
                sprintf('[GetOneFamilyQueryHandler::handle] Family was not found with UUID: %s', $query->getUuid())
            );

            throw new FamilyNotFoundException(
                '[FAMILY][GET] Family was not found with UUID:'.$query->getUuid(), null, 404
            );
        }

        return $family;
    }
}
