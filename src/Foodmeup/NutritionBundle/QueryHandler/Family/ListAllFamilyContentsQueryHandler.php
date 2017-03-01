<?php

namespace Foodmeup\NutritionBundle\QueryHandler\Family;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

use Foodmeup\NutritionBundle\Query\Family\ListAllFamilyContentsQuery;
use Foodmeup\NutritionBundle\Repository\Family\FamilyRepository;
use Foodmeup\NutritionBundle\Repository\Family\FamilyContentRepository;

/**
 * Class ListAllFamilyContentsQueryHandler.
 */
class ListAllFamilyContentsQueryHandler
{
    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FamilyRepository
     */
    private $familyContentRepository;

    /**
     * Constructor.
     *
     * @param Paginator                     $paginator
     * @param LoggerInterface               $logger
     * @param FamilyContentRepository       $familyContentRepository
     */
    public function __construct(Paginator $paginator, LoggerInterface $logger,
                                FamilyContentRepository $familyContentRepository)
    {
        $this->logger = $logger;
        $this->paginator = $paginator;
        $this->familyContentRepository = $familyContentRepository;
    }

    /**
     * @param ListAllFamilyContentsQuery $query
     *
     * @return PaginationInterface
     */
    public function handle(ListAllFamilyContentsQuery $query)
    {
        $query->addFilter('status', $query->getStatus());
        $contents = $this->familyContentRepository->getAllContentsByFamilyUuidAndFilters(
            $query->getFamilyUuid(), $query->getFilters()
        );

        $this->logger->info('List all Family contents');

        $options = ['sortsAllowed' => $query->getSortsAllowed(), 'sort' => $query->getSort(), 'alias' => 'fc'];
        return $this->paginator->paginate($contents, $query->getPage(), $query->getLimit(), $options);
    }
}
