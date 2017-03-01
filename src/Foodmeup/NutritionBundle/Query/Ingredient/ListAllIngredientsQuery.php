<?php

namespace Foodmeup\NutritionBundle\Query\Ingredient;

use Foodmeup\NutritionBundle\Model\CoreStatus;
use Foodmeup\NutritionBundle\Query\BaseQueryListAll;

/**
 * Class ListAllIngredientsQuery.
 */
class ListAllIngredientsQuery extends BaseQueryListAll
{
    /**
     * @var string
     */
    private $status = CoreStatus::TRIGRAM_PUBLISHED;

    /**
     * @var array
     */
    protected $limitsAllowed = [10, 20, 50];

    /**
     * @var array
     */
    protected $filtersAllowed = ['status', 'search', 'content_lang', 'content_slug',
                                 'content_name', 'content_status', 'family_uuid', 'uuids', ];

    /**
     * @var array
     */
    protected $statusAllowed = [CoreStatus::TRIGRAM_PUBLISHED, CoreStatus::TRIGRAM_DISABLED,
                                CoreStatus::TRIGRAM_DELETED, CoreStatus::TRIGRAM_ALL, ];

    /**
     * @var array
     */
    protected $fieldsAllowed = ['uuid', 'origfdcd'];

    /**
     * @var array
     */
    protected $embeddedsAllowed = ['content' => ['uuid', 'slug', 'lang', 'name']];

    /**
     * @var array
     */
    protected $sortsAllowed = ['status', 'createdAt', 'updatedAt'];

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        if ($status != null) {
            $statusList = explode(',', $status);

            foreach ($statusList as $value) {
                if (!in_array($value, $this->statusAllowed)) {
                    throw new \DomainException('The value of this status is not authorized', 400);

                    break;
                }
            }

            $this->status = $status;
        }
    }
}
