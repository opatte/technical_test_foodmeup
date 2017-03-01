<?php

namespace Foodmeup\NutritionBundle\Model;

/**
 * Class CoreStatus
 * > Trigram statuses and their labels
 */
class CoreStatus
{
    const TRIGRAM_ALL           = 'ALL';
    const TRIGRAM_PUBLISHED     = 'PUB';
    const TRIGRAM_DISABLED      = 'DIS';
    const TRIGRAM_DELETED       = 'DEL';

    const ALL                   = 'all';
    const PUBLISHED             = 'published';
    const DISABLED              = 'disabled';
    const DELETED               = 'deleted';

    /**
     * Statuses list and their associated trigram
     *
     * @var array $statuses
     */
    public static $statuses = [
        self::DISABLED  => self::TRIGRAM_DISABLED,
        self::DELETED   => self::TRIGRAM_DELETED,
        self::ALL       => self::TRIGRAM_ALL,
        self::PUBLISHED => self::TRIGRAM_PUBLISHED,
    ];
}
