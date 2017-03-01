<?php

namespace Foodmeup\NutritionBundle\Query\Family;

/**
 * Class GetOneFamilyQuery.
 */
class GetOneFamilyQuery
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * Constructor.
     *
     * @param string $uuid
     *
     * @throws \Exception 'Missing required "uuid" parameter'
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
}
