<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

use Symfony\Component\HttpKernel\KernelInterface;

use Foodmeup\NutritionBundle\DataFixtures\Test\NutritionSpecificFixtureData;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, KernelAwareContext, SnippetAcceptingContext
{
    /**
     * Project kernel.
     *
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * Base url.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Fixture with all status.
     *
     * @var bool
     */
    protected $fixtureWithAllStatus = false;

    /**
     * Construct.
     *
     * @param string $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Set the project kernel (specific for the tests).
     *
     * @param KernelInterface $kernelInterface
     */
    public function setKernel(KernelInterface $kernelInterface)
    {
        $this->kernel = $kernelInterface;
    }

    /**
     * Get the project kernel (specific for the tests).
     *
     * @return KernelInterface
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * Get the entity manager.
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @Given /^Load the fixtures by entities:$/
     *
     * @param TableNode $entitiesTable
     */
    public function loadFixturesByEntities(TableNode $entitiesTable)
    {
        // Get entities list to init
        $entities = $this->castListEntities($entitiesTable);

        // Load fixtures by entities (passed in parameter)
        $loader = new Loader();
        $loader = $this->loadDataFixtureByEntities($loader, $entities);

        // Init & Execute ORMPurger
        $executor = new ORMExecutor($this->getEntityManager(), new ORMPurger());

        $executor->purge();
        $executor->execute($loader->getFixtures(), true);
    }

    /**
     * * Transform Table(BEHAT) in array.
     *
     * @param TableNode $entitiesTable
     *
     * @return array
     */
    private function castListEntities(TableNode $entitiesTable)
    {
        $entities = array();
        foreach ($entitiesTable->getHash() as $entity) {
            $entities[] = [
                'entityName' => $entity['name'],
                'forPagination' => $entity['pagination'] === 'yes' ? true : false,
            ];
        }

        return $entities;
    }

    /**
     * Load the data fixture by entities.
     *
     * @param Loader $loader
     * @param array  $entities
     *
     * @return Loader
     */
    private function loadDataFixtureByEntities(Loader $loader, $entities)
    {
        $dataFixture = new NutritionSpecificFixtureData($entities);
        if ($loader->hasFixture($dataFixture)) {
            unset($dataFixture);

            return $loader;
        }

        $loader->addFixture($dataFixture);

        return $loader;
    }
}
