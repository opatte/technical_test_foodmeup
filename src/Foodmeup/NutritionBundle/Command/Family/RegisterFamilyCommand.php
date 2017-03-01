<?php

namespace Foodmeup\NutritionBundle\Command\Family;

/**
 * Class RegisterFamilyCommand.
 */
class RegisterFamilyCommand
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $origgpcd;

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
}
