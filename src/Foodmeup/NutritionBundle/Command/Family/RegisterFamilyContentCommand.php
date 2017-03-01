<?php

namespace Foodmeup\NutritionBundle\Command\Family;

use Foodmeup\NutritionBundle\Model\Family\FamilyModel;

/**
 * Class RegisterFamilyContentCommand.
 */
class RegisterFamilyContentCommand
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
     * @var FamilyModel
     */
    private $family;

    /**
     * Constructor.
     *
     * @param string            $uuid
     * @param FamilyModel       $familyModel
     *
     * @throws \DomainException 'Missing required "uuid" parameter'
     */
    public function __construct($uuid, FamilyModel $familyModel)
    {
        if ($uuid === null) {
            throw new \DomainException('Missing required "uuid" parameter', 400);
        }

        if ($familyModel->getUuid() === null) {
            throw new \DomainException('The family model is not valid', 400);
        }

        $this->uuid = $uuid;
        $this->family = $familyModel;
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
     * @return FamilyModel
     */
    public function getFamily()
    {
        return $this->family;
    }
}
