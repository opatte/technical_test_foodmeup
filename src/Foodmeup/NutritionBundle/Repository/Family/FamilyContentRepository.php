<?php

namespace Foodmeup\NutritionBundle\Repository\Family;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Foodmeup\NutritionBundle\Repository\BaseRepository;
use Foodmeup\NutritionBundle\Repository\RepositorySlugifyInterface;

/**
 * Class FamilyContentRepository.
 */
class FamilyContentRepository extends BaseRepository implements RepositorySlugifyInterface
{
    const ALIAS_FAMILY = 'f';
    const ALIAS_FAMILY_CONTENT = 'fc';

    /**
     * Get the all family contents by family uuid and filters.
     *
     * @param string $familyUuid
     * @param array  $filters
     *
     * @return Query
     */
    public function getAllContentsByFamilyUuidAndFilters($familyUuid, array $filters = array())
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_FAMILY_CONTENT);
        $this->filterByFamilyUuid($queryBuilder, $familyUuid);

        // Applies the filters on all family contents
        $this->filterByStatus($queryBuilder, $filters, self::ALIAS_FAMILY_CONTENT);
        $this->filterByContentLang($queryBuilder, $filters, self::ALIAS_FAMILY_CONTENT);
        $this->filterByContentSlug($queryBuilder, $filters, self::ALIAS_FAMILY_CONTENT);
        $this->filterByContentName($queryBuilder, $filters, self::ALIAS_FAMILY_CONTENT);

        return $queryBuilder->getQuery();
    }

    /**
     * Filter by family uuid.
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $familyUuid Family uuid
     * @param string       $alias
     */
    private function filterByFamilyUuid(QueryBuilder $queryBuilder, $familyUuid, $alias = 'fc')
    {
        $queryBuilder->leftJoin($alias.'.family', self::ALIAS_FAMILY);
        $queryBuilder->andWhere(self::ALIAS_FAMILY.'.uuid = :family_uuid');
        $queryBuilder->setParameter('family_uuid', $familyUuid);
    }

    /**
     * Get one family content by family uuid and uuid.
     *
     * @param string $familyUuid
     * @param string $contentUuid
     *
     * @return mixed
     */
    public function getOneContentByFamilyUuidAndContentUuid($familyUuid, $contentUuid)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_FAMILY_CONTENT);
        $queryBuilder->andWhere(self::ALIAS_FAMILY_CONTENT.'.uuid = :uuid');
        $queryBuilder->setParameter('uuid', $contentUuid);

        $this->filterByFamilyUuid($queryBuilder, $familyUuid);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * Get one object by slug.
     *
     * @param string $slug
     *
     * @return null|object
     */
    public function getOneBySlug($slug)
    {
        return $this->findOneBy(['slug' => $slug]);
    }
}
