<?php

namespace Foodmeup\NutritionBundle\Model\Ingredient;

use Foodmeup\NutritionBundle\Model\BaseModel;

/**
 * Class IngredientContentModel.
 */
class IngredientContentModel extends BaseModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var IngredientModel
     */
    protected $ingredient;

    /**
     * Constructor.
     *
     * @param string    $uuid
     * @param string    $status
     * @param \DateTime $createdAt
     * @param string    $createdBy
     * @param \DateTime $updatedAt
     * @param string    $updatedBy
     * @param string    $name
     * @param string    $slug
     * @param string    $lang
     * @param IngredientModel $ingredient
     */
    public function __construct($uuid = null, $status = null, \DateTime $createdAt = null, $createdBy = null,
                                \DateTime $updatedAt = null, $updatedBy = null, $name = null, $slug = null,
                                $lang = null, IngredientModel $ingredient = null)
    {
        if ($uuid != null) {
            $this->setUuid($uuid);
        }

        if ($status != null) {
            $this->setStatus($status);
        }

        if ($createdAt != null) {
            $this->setCreatedAt($createdAt);
        }

        if ($createdBy != null) {
            $this->setCreatedBy($createdBy);
        }

        if ($updatedAt != null) {
            $this->setUpdatedAt($updatedAt);
        }

        if ($updatedBy != null) {
            $this->setUpdatedBy($updatedBy);
        }

        if ($name != null) {
            $this->setName($name);
        }

        if ($slug != null) {
            $this->setSlug($slug);
        }

        if ($lang != null) {
            $this->setLang($lang);
        }

        if ($ingredient != null) {
            $this->setIngredient($ingredient);
        }
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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

    /**
     * @param IngredientModel $ingredient
     */
    public function setIngredient(IngredientModel $ingredient)
    {
        $this->ingredient = $ingredient;
    }
}
