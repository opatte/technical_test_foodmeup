<?php

namespace Foodmeup\NutritionBundle\Rest\Family;

use Hateoas\Configuration as Hateoas;
use Hateoas\UrlGenerator\UrlGeneratorInterface;
use Hateoas\Configuration\Provider\RelationProviderInterface;

use Foodmeup\NutritionBundle\Entity\Family\FamilyContent;

/**
 * Class FamilyUrlGenerator.
 */
class FamilyContentUrlGenerator implements UrlGeneratorInterface, RelationProviderInterface
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
        if ($object instanceof FamilyContent) {
            $relations = [
                new Hateoas\Relation('self', null, ['uuid' => $object->getUuid()]),
                new Hateoas\Relation('family', null, ['uuid' => $object->getFamily()->getUuid()]),
            ];
        }

        return $relations;
    }
}
