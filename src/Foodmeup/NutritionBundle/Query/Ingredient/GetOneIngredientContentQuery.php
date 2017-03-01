<?php

namespace Foodmeup\NutritionBundle\Query\Ingredient;

/**
 * Class GetOneIngredientContentQuery.
 */
class GetOneIngredientContentQuery
{
    /**
     * @var string
     */
    private $ingredientUuid;

    /**
     * @var string
     */
    private $uuid;

    /**
     * Constructor.
     *
     * @param string $ingredientUuid
     * @param string $uuid
     *
     * @throws \Exception 'Missing required "uuid" OR "ingredient uuid" parameter'
     */
    public function __construct($ingredientUuid, $uuid)
    {
        if ($ingredientUuid === null || $uuid === null) {
            throw new \DomainException('Missing required "uuid" OR "ingredient uuid" parameter', 400);
        }

        $this->ingredientUuid = $ingredientUuid;
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getIngredientUuid()
    {
        return $this->ingredientUuid;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }
}
