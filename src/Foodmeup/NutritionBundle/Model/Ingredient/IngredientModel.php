<?php

namespace Foodmeup\NutritionBundle\Model\Ingredient;

use Doctrine\Common\Collections\ArrayCollection;

use Foodmeup\NutritionBundle\Model\BaseModel;
use Foodmeup\NutritionBundle\Model\Family\FamilyModel;

/**
 * Class IngredientModel.
 */
class IngredientModel extends BaseModel
{
    /**
     * @var int
     */
    protected $origfdcd;

    /**
     * @var ArrayCollection
     */
    protected $contents;

    /**
     * @var FamilyModel
     */
    protected $family;

    /**
     * Constructor.
     *
     * @param string          $uuid
     * @param string          $status
     * @param \DateTime       $createdAt
     * @param string          $createdBy
     * @param \DateTime       $updatedAt
     * @param string          $updatedBy
     * @param int             $origfdcd
     * @param FamilyModel $family
     */
    public function __construct($uuid = null, $status = null, \DateTime $createdAt = null, $createdBy = null,
                                \DateTime $updatedAt = null, $updatedBy = null, $origfdcd = null,
                                FamilyModel $family = null)
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

        if ($origfdcd != null) {
            $this->setOrigfdcd($origfdcd);
        }

        if ($family != null) {
            $this->setFamily($family);
        }

        $this->contents = new ArrayCollection();
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
     * @param IngredientContentModel $content
     *
     * @return $this
     */
    public function addContent(IngredientContentModel $content)
    {
        if ($this->contents->contains($content) === false) {
            $this->contents->add($content);
        }

        return $this;
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
