<?php

namespace Foodmeup\NutritionBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Foodmeup\NutritionBundle\Model\CoreStatus;

/**
 * Class BaseRepository.
 */
class BaseRepository extends EntityRepository
{
    /**
     * Save the object.
     *
     * @param $object object to save.
     * @param bool $flush
     * @param bool $clear
     */
    public function save($object, $flush = true, $clear = false)
    {
        $this->_em->persist($object);

        if ($flush) {
            $this->_em->flush();
        }

        if ($clear) {
            $this->_em->clear();
        }
    }

    /**
     * Filter by status.
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $filters
     * @param string       $alias
     */
    protected function filterByStatus(QueryBuilder $queryBuilder, array $filters, $alias = 'f')
    {
        if (isset($filters['status']) === true && $filters['status'] != null
            && strtoupper($filters['status']) != CoreStatus::TRIGRAM_ALL) {
            // Allowed the filter "status" in multiple values
            $filterValues = explode(',', $filters['status']);

            $searchByStatus = array();
            foreach ($filterValues as $key => $filterValue) {
                $searchByStatus[] = $queryBuilder->expr()->like($alias.'.status', ':status'.$key);

                $queryBuilder->setParameter('status'.$key, '%'.$filterValue.'%');
            }

            $queryBuilder->andWhere(implode(' OR ', $searchByStatus));
        }
    }

    /**
     * Filter by uuids.
     *
     * @param QueryBuilder $queryBuilder
     * @param $filters
     * @param string $alias
     */
    protected function filterByUuids(QueryBuilder $queryBuilder, array $filters, $alias = 'f')
    {
        if (array_key_exists('uuids', $filters) && $filters['uuids'] != null) {
            $uuids = explode(',', str_replace(' ', null, $filters['uuids']));
            if ($uuids) {
                if (is_array($uuids)) {
                    $queryBuilder->andWhere("{$alias}.uuid IN (:uuids)");
                    $queryBuilder->setParameter('uuids', $uuids);
                }
            }
        }
    }

    /**
     * Filter by search. (in "<PARENT>_content").
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $filters
     * @param string       $alias
     */
    protected function filterBySearch(QueryBuilder $queryBuilder, array $filters, $alias = 'fc')
    {
        if (isset($filters['search']) === true && $filters['search'] !== null) {
            $search = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $filters['search']);
            $words = explode(' ', $search);

            foreach ($words as $key => $word) {
                $queryBuilder->andWhere($alias.'.name LIKE :search'.$key);
                $queryBuilder->setParameter('search'.$key, '%'.$word.'%');
            }
        }
    }

    /**
     * Filter by lang (in "<PARENT>_content").
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $filters
     * @param string       $alias
     */
    protected function filterByContentLang(QueryBuilder $queryBuilder, array $filters, $alias = 'fc')
    {
        if (isset($filters['content_lang']) === true && $filters['content_lang'] !== null) {
            $queryBuilder->andWhere($alias.'.lang = :content_lang');
            $queryBuilder->setParameter('content_lang', $filters['content_lang']);
        }
    }

    /**
     * Filter by slug (in "<PARENT>_content").
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $filters
     * @param string       $alias
     */
    protected function filterByContentSlug(QueryBuilder $queryBuilder, array $filters, $alias = 'fc')
    {
        if (isset($filters['content_slug']) === true && $filters['content_slug'] !== null) {
            $queryBuilder->andWhere($alias.'.slug = :content_slug');
            $queryBuilder->setParameter('content_slug', $filters['content_slug']);
        }
    }

    /**
     * Filter by name (in "<PARENT>_content").
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $filters
     * @param string       $alias
     */
    protected function filterByContentName(QueryBuilder $queryBuilder, array $filters, $alias = 'fc')
    {
        if (isset($filters['content_name']) === true && $filters['content_name'] !== null) {
            $nameValues = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $filters['content_name']);
            $words = explode(' ', $nameValues);

            foreach ($words as $key => $word) {
                $queryBuilder->andWhere($alias.'.name LIKE :content_name'.$key);
                $queryBuilder->setParameter('content_name'.$key, '%'.$word.'%');
            }
        }
    }

    /**
     * Filter by content_status (in "<PARENT>_content").
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $filters
     * @param string       $alias
     */
    protected function filterByContentStatus(QueryBuilder $queryBuilder, array $filters, $alias = 'fc')
    {
        if (isset($filters['content_status']) === true && $filters['content_status'] != null
            && strtoupper($filters['content_status']) != CoreStatus::TRIGRAM_ALL) {
            // Allowed the filter "content_status" in multiple values
            $filterValues = explode(',', $filters['content_status']);

            $searchByStatus = array();
            foreach ($filterValues as $key => $filterValue) {
                $searchByStatus[] = $queryBuilder->expr()->like($alias.'.status', ':content_status'.$key);

                $queryBuilder->setParameter('content_status'.$key, '%'.$filterValue.'%');
            }

            $queryBuilder->andWhere(implode(' OR ', $searchByStatus));
        }
    }
}
