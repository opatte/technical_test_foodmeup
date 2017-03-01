<?php

namespace Foodmeup\NutritionBundle\QueryHandler\Family;

use Psr\Log\LoggerInterface;

use Foodmeup\NutritionBundle\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

use Foodmeup\NutritionBundle\Query\Family\ListAllFamiliesQuery;
use Foodmeup\NutritionBundle\Repository\Family\FamilyRepository;

/**
 * Class ListAllFamiliesQueryHandler.
 */
class ListAllFamiliesQueryHandler
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
    private $familyRepository;

    /**
     * Constructor.
     *
     * @param LoggerInterface       $logger
     * @param Paginator             $paginator
     * @param FamilyRepository      $familyRepository
     */
    public function __construct(Paginator $paginator, LoggerInterface $logger, FamilyRepository $familyRepository)
    {
        $this->paginator = $paginator;
        $this->logger = $logger;
        $this->familyRepository = $familyRepository;
    }

    /**
     * @param ListAllFamiliesQuery $query
     *
     * @return PaginationInterface
     */
    public function handle(ListAllFamiliesQuery $query)
    {
        $query->addFilter('status', $query->getStatus());
        $families = $this->familyRepository->getAllFamiliesByFilters(
            $query->getFilters(), $this->getExposeUuidOnly($query)
        );

        $this->logger->info(sprintf('[ListFamiliesQueryHandler::handle] List all families'));

        $options = ['sortsAllowed' => $query->getSortsAllowed(), 'sort' => $query->getSort(), 'alias' => 'f'];
        return $this->paginator->paginate($families, $query->getPage(), $query->getLimit(), $options);
    }

    /**
     * @param ListAllFamiliesQuery $query
     *
     * @return bool
     */
    private function getExposeUuidOnly(ListAllFamiliesQuery $query)
    {
        if (in_array('uuid', $query->getFields())) {
            return true;
        }

        return false;
    }
}
