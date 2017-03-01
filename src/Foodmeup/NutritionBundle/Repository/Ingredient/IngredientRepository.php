<?php

namespace Foodmeup\NutritionBundle\Repository\Ingredient;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Foodmeup\NutritionBundle\Repository\BaseRepository;

/**
 * Class IngredientRepository.
 */
class IngredientRepository extends BaseRepository
{
    const ALIAS_INGREDIENT = 'i';
    const ALIAS_INGREDIENT_CONTENT = 'ic';
    const ALIAS_FAMILY = 'f';

    /**
     * Get the all ingredients by filters.
     *
     * @param array $filters
     *
     * @return Query
     */
    public function getAllIngredientsByFilters(array $filters = array())
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_INGREDIENT);

        $queryBuilder->select(self::ALIAS_INGREDIENT, self::ALIAS_INGREDIENT_CONTENT);
        $queryBuilder->leftJoin(self::ALIAS_INGREDIENT.'.contents', self::ALIAS_INGREDIENT_CONTENT);

        // Applies the filters on all ingredients
        $this->filterByStatus($queryBuilder, $filters, self::ALIAS_INGREDIENT);
        $this->filterByUuids($queryBuilder, $filters, self::ALIAS_INGREDIENT);

        $this->filterBySearch($queryBuilder, $filters, self::ALIAS_INGREDIENT_CONTENT);
        $this->filterByContentLang($queryBuilder, $filters, self::ALIAS_INGREDIENT_CONTENT);
        $this->filterByContentSlug($queryBuilder, $filters, self::ALIAS_INGREDIENT_CONTENT);
        $this->filterByContentName($queryBuilder, $filters, self::ALIAS_INGREDIENT_CONTENT);
        $this->filterByContentStatus($queryBuilder, $filters, self::ALIAS_INGREDIENT_CONTENT);

        $this->filterByFamilyUuid($queryBuilder, $filters);

        return $queryBuilder->getQuery();
    }

    /**
     * Filter by family uuid.
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $filters
     */
    private function filterByFamilyUuid(QueryBuilder $queryBuilder, $filters)
    {
        if (isset($filters['family_uuid']) === true && $filters['family_uuid'] !== null) {
            $queryBuilder->leftJoin(self::ALIAS_INGREDIENT.'.family', self::ALIAS_FAMILY);
            $queryBuilder->andWhere(self::ALIAS_FAMILY.'.uuid = :family_uuid');
            $queryBuilder->setParameter('family_uuid', $filters['family_uuid']);
        }
    }
}
