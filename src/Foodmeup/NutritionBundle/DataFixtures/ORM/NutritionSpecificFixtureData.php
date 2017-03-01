<?php

namespace Foodmeup\NutritionBundle\DataFixtures\ORM;

use Nelmio\Alice\Fixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

/**
 * Class NutritionSpecificFixtureData.
 */
class NutritionSpecificFixtureData implements FixtureInterface
{
    /**
     * Load the project fixtures - (Env: DEV).
     *
     * @param ObjectManager $objectManager
     */
    public function load(ObjectManager $objectManager)
    {
        Fixtures::load(__DIR__.'/../nutrition_template.yml', $objectManager);

        // FIXTURES - FAMILY
        Fixtures::load(__DIR__.'/family/family.yml', $objectManager);
        Fixtures::load(__DIR__.'/family/family_content.yml', $objectManager);

        // FIXTURES - INGREDIENT
        Fixtures::load(__DIR__.'/ingredient/ingredient.yml', $objectManager);
        Fixtures::load(__DIR__.'/ingredient/ingredient_content.yml', $objectManager);
    }
}
