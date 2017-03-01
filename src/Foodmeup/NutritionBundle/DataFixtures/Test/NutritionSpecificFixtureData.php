<?php

namespace Foodmeup\NutritionBundle\DataFixtures\Test;

use Nelmio\Alice\Fixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

/**
 * Class NutritionSpecificFixtureData.
 */
class NutritionSpecificFixtureData implements FixtureInterface
{
    /**
     * @var array Matching list entity with fixtures
     */
    private $listFixtures;

    /**
     * @var array
     */
    private static $entitiesName = [
        // FIXTURES - FAMILY
        'family/family',
        'family/family_content',

        // FIXTURES - INGREDIENT
        'ingredient/ingredient',
        'ingredient/ingredient_content',
    ];

    /**
     * Constructor.
     *
     * @param array $listFixtures Matching list entity with fixtures
     */
    public function __construct($listFixtures)
    {
        $this->listFixtures = $listFixtures;
    }

    /**
     * Load the project fixtures - (Env: TEST).
     *
     * @param ObjectManager $objectManager
     */
    public function load(ObjectManager $objectManager)
    {
        Fixtures::load(__DIR__.'/../nutrition_template.yml', $objectManager);

        foreach ($this->listFixtures as $item) {
            if (in_array($item['entityName'], self::$entitiesName) === true) {
                $prefixPagination = $item['forPagination'] === true ? 'Paginator/' : 'Simple/';
                $pathFixture = __DIR__.'/'.$prefixPagination.$item['entityName'].'.yml';

                // Load fixtures
                Fixtures::load($pathFixture, $objectManager);
            }
        }
    }
}
