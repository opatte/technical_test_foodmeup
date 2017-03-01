<?php

namespace Foodmeup\NutritionBundle\Repository\Ingredient;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Foodmeup\NutritionBundle\Repository\BaseRepository;
use Foodmeup\NutritionBundle\Repository\RepositorySlugifyInterface;

/**
 * Class IngredientContentRepository.
 */
class IngredientContentRepository extends BaseRepository implements RepositorySlugifyInterface
{
    const ALIAS_INGREDIENT = 'i';
    const ALIAS_INGREDIENT_CONTENT = 'ic';

    /**
     * Get the all ingredient contents by ingredient uuid and filters.
     *
     * @param string $ingredientUuid
     * @param array  $filters
     *
     * @return Query
     */
    public function getAllIngredientContentsByIngredientUuidAndFilters($ingredientUuid, array $filters = array())
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_INGREDIENT_CONTENT);
        $this->filterByIngredientUuid($queryBuilder, $ingredientUuid);

        // Applies the filters on all ingredient contents
        $this->filterByStatus($queryBuilder, $filters, self::ALIAS_INGREDIENT_CONTENT);
        $this->filterByContentLang($queryBuilder, $filters, self::ALIAS_INGREDIENT_CONTENT);
        $this->filterByContentSlug($queryBuilder, $filters, self::ALIAS_INGREDIENT_CONTENT);
        $this->filterByContentName($queryBuilder, $filters, self::ALIAS_INGREDIENT_CONTENT);

        return $queryBuilder->getQuery();
    }

    /**
     * Filter by ingredient uuid.
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $ingredientUuid    Ingredient uuid
     * @param string       $alias
     */
    private function filterByIngredientUuid(QueryBuilder $queryBuilder, $ingredientUuid, $alias = 'ic')
    {
        $queryBuilder->leftJoin($alias.'.ingredient', self::ALIAS_INGREDIENT);
        $queryBuilder->andWhere(self::ALIAS_INGREDIENT.'.uuid = :ingredient_uuid');
        $queryBuilder->setParameter('ingredient_uuid', $ingredientUuid);
    }

    /**
     * Find one ingredient content by ingredient uuid and uuid.
     *
     * @param string $ingredientUuid
     * @param string $uuid
     *
     * @return mixed
     */
    public function findOneIngredientContentByIngredientUuidAndUuid($ingredientUuid, $uuid)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_INGREDIENT_CONTENT);
        $queryBuilder->andWhere(self::ALIAS_INGREDIENT_CONTENT.'.uuid = :uuid');
        $queryBuilder->setParameter('uuid', $uuid);

        $this->filterByIngredientUuid($queryBuilder, $ingredientUuid);

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
