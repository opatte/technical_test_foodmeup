<?php

namespace Foodmeup\NutritionBundle\Command\Ingredient;

use Foodmeup\NutritionBundle\Model\Ingredient\IngredientModel;

/**
 * Class RegisterIngredientContentCommand.
 */
class RegisterIngredientContentCommand
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var IngredientModel
     */
    private $ingredient;

    /**
     * Constructor.
     *
     * @param string     $uuid
     * @param IngredientModel $ingredientModel
     *
     * @throws \DomainException 'Missing required "uuid" parameter'
     */
    public function __construct($uuid, IngredientModel $ingredientModel)
    {
        if ($uuid === null) {
            throw new \DomainException('Missing required "uuid" parameter', 400);
        }

        if ($ingredientModel->getUuid() === null) {
            throw new \DomainException('The ingredient model is not valid', 400);
        }

        $this->uuid = $uuid;
        $this->ingredient = $ingredientModel;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return IngredientModel
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }
}
