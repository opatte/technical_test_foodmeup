<?php

namespace Foodmeup\NutritionBundle\Command\Ingredient;

use Foodmeup\NutritionBundle\Model\Family\FamilyModel;

/**
 * Class RegisterIngredientCommand.
 */
class RegisterIngredientCommand
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var int
     */
    protected $origfdcd;

    /**
     * @var FamilyModel;
     */
    protected $family;

    /**
     * Constructor.
     *
     * @param string $uuid
     *
     * @throws \DomainException 'Missing required "uuid" parameter'
     */
    public function __construct($uuid)
    {
        if ($uuid === null) {
            throw new \DomainException('Missing required "uuid" parameter', 400);
        }

        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return int
     */
    public function getOrigfdcd()
    {
        return $this->origfdcd;
    }

    /**
     * @param int $origfdcd
     */
    public function setOrigfdcd($origfdcd)
    {
        $this->origfdcd = $origfdcd;
    }

    /**
     * @return FamilyModel
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @param FamilyModel $family
     */
    public function setFamily(FamilyModel $family)
    {
        $this->family = $family;
    }
}
