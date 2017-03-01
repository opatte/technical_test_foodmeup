<?php

namespace Foodmeup\NutritionBundle\Query\Family;

/**
 * Class GetOneFamilyContentQuery.
 */
class GetOneFamilyContentQuery
{
    /**
     * @var string
     */
    private $familyUuid;

    /**
     * @var string
     */
    private $contentUuid;

    /**
     * GetOneFamilyContentQuery constructor.
     *
     * @param string $familyUuid
     * @param string $contentUuid
     */
    public function __construct($familyUuid, $contentUuid)
    {
        if ($familyUuid === null || $contentUuid === null) {
            throw new \DomainException('Missing required "uuid" OR "family uuid" parameter', 400);
        }

        $this->familyUuid = $familyUuid;
        $this->contentUuid = $contentUuid;
    }

    /**
     * @return string
     */
    public function getContentUuid()
    {
        return $this->contentUuid;
    }

    /**
     * @return string
     */
    public function getFamilyUuid()
    {
        return $this->familyUuid;
    }
}
