<?php

namespace Foodmeup\NutritionBundle\Rest\Ingredient;

use Hateoas\Configuration as Hateoas;
use Hateoas\UrlGenerator\UrlGeneratorInterface;
use Hateoas\Configuration\Provider\RelationProviderInterface;
use Foodmeup\NutritionBundle\Entity\Ingredient\IngredientContent;

/**
 * Class IngredientUrlGenerator.
 */
class IngredientContentUrlGenerator implements UrlGeneratorInterface, RelationProviderInterface
{
    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $absolute
     *
     * @return string
     */
    public function generate($name, array $parameters, $absolute = false)
    {
        return '';
    }

    /**
     * @param object $object
     *
     * @return array
     */
    public function getRelations($object)
    {
        $relations = [];
        if ($object instanceof IngredientContent) {
            $relations = [
                new Hateoas\Relation('self', null, ['uuid' => $object->getUuid()]),
                new Hateoas\Relation('ingredient', null, ['uuid' => $object->getIngredient()->getUuid()]),
            ];
        }

        return $relations;
    }
}
