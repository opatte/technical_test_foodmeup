<?php

namespace Foodmeup\NutritionBundle\Repository\Family;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Foodmeup\NutritionBundle\Repository\BaseRepository;

/**
 * Class FamilyRepository.
 */
class FamilyRepository extends BaseRepository
{
    const ALIAS_FAMILY = 'f';
    const ALIAS_FAMILY_CONTENT = 'fc';

    /**
     * Get all families by filters.
     *
     * @param array $filters
     * @param bool  $exposeUuidOnly
     *
     * @return Query
     */
    public function getAllFamiliesByFilters($filters = array(), $exposeUuidOnly = false)
    {
        $queryBuilder = $this->getAllFamiliesQueryBuilder($filters);
        if ($exposeUuidOnly) {
            $queryBuilder->select(self::ALIAS_FAMILY.'.uuid')->distinct(true);
        }

        return $queryBuilder->getQuery();
    }

    /**
     * Get the families (UUID + Content title) by array "family UUID".
     *
     * @param array  $familyUuids
     * @param string $lang
     *
     * @return array
     */
    public function getFamiliesByFamilyUuids($familyUuids, $lang)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_FAMILY)
            ->select(self::ALIAS_FAMILY.'.uuid', self::ALIAS_FAMILY_CONTENT.'.title')
            ->leftJoin('p.contents', self::ALIAS_FAMILY_CONTENT)
            ->andWhere('p.uuid IN (:familyUuids)')
            ->setParameter('familyUuids', $familyUuids)
        ;

        $this->filterByContentLang($queryBuilder, ['content_lang' => $lang], self::ALIAS_FAMILY_CONTENT);
        return $queryBuilder->getQuery()->getArrayResult();
    }

    /**
     * Get all families - QUERY BUILDER.
     *
     * @param array $filters
     *
     * @return QueryBuilder
     */
    private function getAllFamiliesQueryBuilder($filters = [])
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_FAMILY);

        $queryBuilder->select(self::ALIAS_FAMILY, self::ALIAS_FAMILY_CONTENT);
        $queryBuilder->leftJoin(self::ALIAS_FAMILY.'.contents', self::ALIAS_FAMILY_CONTENT);

        // Applies the filters on all ingredients
        $this->filterByStatus($queryBuilder, $filters, self::ALIAS_FAMILY);
        $this->filterByUuids($queryBuilder, $filters, self::ALIAS_FAMILY);

        $this->filterBySearch($queryBuilder, $filters, self::ALIAS_FAMILY_CONTENT);
        $this->filterByContentLang($queryBuilder, $filters, self::ALIAS_FAMILY_CONTENT);
        $this->filterByContentSlug($queryBuilder, $filters, self::ALIAS_FAMILY_CONTENT);
        $this->filterByContentName($queryBuilder, $filters, self::ALIAS_FAMILY_CONTENT);
        $this->filterByContentStatus($queryBuilder, $filters, self::ALIAS_FAMILY_CONTENT);

        return $queryBuilder;
    }
}
