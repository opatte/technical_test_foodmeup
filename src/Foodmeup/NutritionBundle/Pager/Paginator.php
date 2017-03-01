<?php

namespace Foodmeup\NutritionBundle\Pager;

use Knp\Component\Pager\Paginator as KnpPaginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class Paginator.
 */
class Paginator
{
    /**
     * @var KnpPaginator
     */
    private $knpPaginator;

    /**
     * Constructor.
     *
     * @param KnpPaginator $knpPaginator
     */
    public function __construct(KnpPaginator $knpPaginator)
    {
        $this->knpPaginator = $knpPaginator;
    }

    /**
     * Paginate.
     *
     * @param mixed $target
     * @param int   $page
     * @param int   $limit
     * @param array $options
     *
     * @return PaginationInterface
     */
    public function paginate($target, $page = 1, $limit = 10, array $options = array())
    {
        return $this->knpPaginator->paginate($target, $page, $limit, $this->filterBySort($options));
    }

    /**
     * Filter by sort.
     *
     * @param array $options
     *
     * @return array
     */
    private function filterBySort(array $options = array())
    {
        // IF the <SORT> is not authorized THEN return array
        if (empty($options) || empty($options['sortsAllowed'])) {
            return [];
        }

        // IF the <SORT> is null THEN return the sort by default
        $filterSort = [
            'defaultSortFieldName' => $options['alias'].'.updatedAt',
            'sortFieldParameterName' => 'sort_by',
            'defaultSortDirection' => 'DESC',
        ];

        if ($options['sort'] != null) {
            $values = explode(' ', $options['sort']);

            $filterSort = [
                'defaultSortFieldName' => $options['alias'].'.'.$values[0],
                'sortFieldParameterName' => 'sort_by',
                'defaultSortDirection' => isset($values[1]) === true ? $values[1] : 'ASC',
            ];
        }

        return $filterSort;
    }
}
