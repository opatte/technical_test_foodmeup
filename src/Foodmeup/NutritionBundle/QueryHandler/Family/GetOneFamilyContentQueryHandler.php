<?php

namespace Foodmeup\NutritionBundle\QueryHandler\Family;

use Psr\Log\LoggerInterface;
use Foodmeup\NutritionBundle\Query\Family\GetOneFamilyContentQuery;
use Foodmeup\NutritionBundle\Repository\Family\FamilyContentRepository;
use Foodmeup\NutritionBundle\Exception\Family\FamilyContentNotFoundException;

/**
 * Class GetOneFamilyContentQueryHandler.
 */
class GetOneFamilyContentQueryHandler
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FamilyContentRepository
     */
    private $familyContentRepository;

    /**
     * Constructor.
     *
     * @param FamilyContentRepository $familyContentRepository
     * @param LoggerInterface             $logger
     */
    public function __construct(FamilyContentRepository $familyContentRepository, LoggerInterface $logger)
    {
        $this->familyContentRepository = $familyContentRepository;
        $this->logger = $logger;
    }

    /**
     * @param GetOneFamilyContentQuery $query
     *
     * @return object
     */
    public function handle(GetOneFamilyContentQuery $query)
    {
        $familyContent = $this->familyContentRepository->getOneContentByFamilyUuidAndContentUuid(
            $query->getFamilyUuid(),
            $query->getContentUuid()
        );

        if ($familyContent === null) {
            $this->logger->warning(
                sprintf(
                    '[GetOneFamilyContentQueryHandler::handle] Family content was not found with "familyUuid" ("%s") and "uuid" ("%s")',
                    $query->getFamilyUuid(), $query->getContentUuid()
                )
            );

            throw new FamilyContentNotFoundException(
                sprintf('[FAMILY_CONTENT][GET] Family content was not found with "familyUuid" ("%s") and "uuid" ("%s")',
                    $query->getFamilyUuid(), $query->getContentUuid()), null, 404
            );
        }

        return $familyContent;
    }
}
