<?php

namespace Foodmeup\NutritionBundle\Model\Family;

use Doctrine\Common\Collections\ArrayCollection;

use Foodmeup\NutritionBundle\Model\BaseModel;
use Foodmeup\NutritionBundle\Model\Ingredient\IngredientModel;

/**
 * Class FamilyModel.
 */
class FamilyModel extends BaseModel
{
    /**
     * @var string
     */
    protected $origgpcd;

    /**
     * @var ArrayCollection
     */
    protected $contents;

    /**
     * @var ArrayCollection
     */
    protected $ingredients;

    /**
     * Constructor.
     *
     * @param string    $uuid
     * @param string    $status
     * @param \DateTime $createdAt
     * @param string    $createdBy
     * @param \DateTime $updatedAt
     * @param string    $updatedBy
     * @param string    $origgpcd
     */
    public function __construct($uuid = null, $status = null, \DateTime $createdAt = null, $createdBy = null,
                                \DateTime $updatedAt = null, $updatedBy = null, $origgpcd = null)
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

        if ($origgpcd != null) {
            $this->setOriggpcd($origgpcd);
        }

        $this->contents = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getOriggpcd()
    {
        return $this->origgpcd;
    }

    /**
     * @param string $origgpcd
     */
    public function setOriggpcd($origgpcd)
    {
        $this->origgpcd = $origgpcd;
    }

    /**
     * Get contents.
     *
     * @return ArrayCollection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Add the content.
     *
     * @param FamilyContentModel $content
     *
     * @return $this
     */
    public function addContent(FamilyContentModel $content)
    {
        if ($this->contents->contains($content) === false) {
            $this->contents->add($content);
        }

        return $this;
    }

    /**
     * Get ingredients.
     *
     * @return ArrayCollection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Add the ingredient.
     *
     * @param IngredientModel $ingredient
     *
     * @return $this
     */
    public function addIngredient(IngredientModel $ingredient)
    {
        if ($this->ingredients->contains($ingredient) === false) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }
}
